import { Head, Link, router } from '@inertiajs/react';
import { FormEvent, useCallback, useEffect, useState } from 'react';
import { toast } from 'sonner';
import AdditionalFieldInput from '@/components/posts/additional-field-input';
import Heading from '@/components/heading';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Spinner } from '@/components/ui/spinner';
import { apiRequest } from '@/lib/api';
import {
    buildPostPayload,
    mapAdditionalFieldsToValues,
    mergeFieldValues,
    normalizeAdditionalField,
    normalizePost,
    selectValue,
} from '@/lib/posts';
import type { AdditionalFieldDefinition, Category, Post, PostAdditionalField } from '@/types/cms';

type Props = {
    uuid?: string;
};

export default function PostForm({ uuid }: Props) {
    const isEditing = Boolean(uuid);

    const [loading, setLoading] = useState(true);
    const [submitting, setSubmitting] = useState(false);
    const [categories, setCategories] = useState<Category[]>([]);
    const [fields, setFields] = useState<AdditionalFieldDefinition[]>([]);
    const [title, setTitle] = useState('');
    const [description, setDescription] = useState('');
    const [categoryUuid, setCategoryUuid] = useState('');
    const [fieldValues, setFieldValues] = useState<Record<string, string>>({});
    const [error, setError] = useState<string | null>(null);

    const loadCategoryFields = useCallback(
        async (target: string, existingValues: Record<string, string> = {}) => {
            if (!target) {
                setFields([]);
                setFieldValues({});

                return;
            }

            const data = await apiRequest<AdditionalFieldDefinition[]>(
                `/additional-fields?target=${target}`,
            );

            const definitions = (Array.isArray(data) ? data : [])
                .map(normalizeAdditionalField)
                .filter((field): field is PostAdditionalField => field !== null);

            setFields(definitions);
            setFieldValues(mergeFieldValues(definitions, existingValues));
        },
        [],
    );

    useEffect(() => {
        const bootstrap = async () => {
            try {
                const categoriesData = await apiRequest<Category[]>('/categories');
                setCategories(categoriesData);

                if (isEditing && uuid) {
                    const post = normalizePost(
                        await apiRequest<Post>(`/posts/${uuid}`),
                    );
                    const values = mapAdditionalFieldsToValues(post.additional_fields);

                    setTitle(post.title);
                    setDescription(post.description);
                    setCategoryUuid(post.category_uuid);

                    if (post.category_uuid) {
                        await loadCategoryFields(post.category_uuid, values);
                    }
                }
            } catch (loadError) {
                toast.error(
                    loadError instanceof Error
                        ? loadError.message
                        : 'Falha ao carregar dados.',
                );
            } finally {
                setLoading(false);
            }
        };

        void bootstrap();
    }, [isEditing, loadCategoryFields, uuid]);

    const handleCategoryChange = async (value: string) => {
        setCategoryUuid(value);

        try {
            await loadCategoryFields(value, fieldValues);
        } catch (loadError) {
            toast.error(
                loadError instanceof Error
                    ? loadError.message
                    : 'Falha ao carregar campos adicionais.',
            );
        }
    };

    const handleSubmit = async (event: FormEvent<HTMLFormElement>) => {
        event.preventDefault();
        setError(null);
        setSubmitting(true);

        const payload = buildPostPayload(
            title,
            description,
            categoryUuid,
            fields,
            fieldValues,
        );

        try {
            if (isEditing && uuid) {
                await apiRequest<Post>(`/posts/${uuid}`, {
                    method: 'PUT',
                    body: JSON.stringify(payload),
                });
                toast.success('Post atualizado com sucesso.');
            } else {
                await apiRequest<Post>('/posts', {
                    method: 'POST',
                    body: JSON.stringify(payload),
                });
                toast.success('Post criado com sucesso.');
            }

            router.visit('/posts');
        } catch (submitError) {
            const message =
                submitError instanceof Error
                    ? submitError.message
                    : 'Falha ao salvar post.';
            setError(message);
            toast.error(message);
        } finally {
            setSubmitting(false);
        }
    };

    if (loading) {
        return (
            <div className="flex justify-center py-12">
                <Spinner className="size-6" />
            </div>
        );
    }

    return (
        <>
            <Head title={isEditing ? 'Editar post' : 'Novo post'} />

            <div className="mx-auto max-w-2xl space-y-6">
                <Heading
                    title={isEditing ? 'Editar post' : 'Novo post'}
                    description="Preencha os dados do post e os campos adicionais da categoria."
                />

                <form onSubmit={handleSubmit} className="space-y-6">
                    <div className="grid gap-2">
                        <Label htmlFor="title">Título</Label>
                        <Input
                            id="title"
                            value={title}
                            onChange={(event) => setTitle(event.target.value)}
                            required
                        />
                    </div>

                    <div className="grid gap-2">
                        <Label htmlFor="description">Descrição</Label>
                        <Input
                            id="description"
                            value={description}
                            onChange={(event) => setDescription(event.target.value)}
                            required
                        />
                    </div>

                    <div className="grid gap-2">
                        <Label>Categoria</Label>
                        <Select
                            value={selectValue(categoryUuid)}
                            onValueChange={(value) => void handleCategoryChange(value)}
                            required
                        >
                            <SelectTrigger className="w-full">
                                <SelectValue placeholder="Selecione uma categoria" />
                            </SelectTrigger>
                            <SelectContent>
                                {categories.map((category) => (
                                    <SelectItem
                                        key={category.uuid}
                                        value={category.uuid}
                                    >
                                        {category.name}
                                    </SelectItem>
                                ))}
                            </SelectContent>
                        </Select>
                    </div>

                    {fields.length > 0 && (
                        <div className="space-y-4 rounded-xl border p-4">
                            <p className="text-sm font-medium">Campos adicionais</p>

                            {fields.map((field) => (
                                <AdditionalFieldInput
                                    key={field.uuid}
                                    field={field}
                                    value={fieldValues[field.uuid] ?? ''}
                                    onChange={(value) =>
                                        setFieldValues((current) => ({
                                            ...current,
                                            [field.uuid]: value,
                                        }))
                                    }
                                />
                            ))}
                        </div>
                    )}

                    <InputError message={error ?? undefined} />

                    <div className="flex gap-3">
                        <Button type="submit" disabled={submitting}>
                            {submitting ? 'Salvando...' : 'Salvar'}
                        </Button>

                        <Button variant="outline" asChild>
                            <Link href="/posts">Cancelar</Link>
                        </Button>
                    </div>
                </form>
            </div>
        </>
    );
}

PostForm.layout = {
    breadcrumbs: [
        { title: 'Dashboard', href: '/dashboard' },
        { title: 'Posts', href: '/posts' },
        { title: 'Formulário', href: '#' },
    ],
};
