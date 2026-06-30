import { Head, Link } from '@inertiajs/react';
import { Plus } from 'lucide-react';
import { useCallback, useEffect, useState } from 'react';
import { toast } from 'sonner';
import AdditionalFieldsList from '@/components/posts/additional-fields-list';
import Heading from '@/components/heading';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Spinner } from '@/components/ui/spinner';
import { apiRequest } from '@/lib/api';
import { normalizePosts } from '@/lib/posts';
import type { Post } from '@/types/cms';

export default function PostsIndex() {
    const [posts, setPosts] = useState<Post[]>([]);
    const [loading, setLoading] = useState(true);

    const loadPosts = useCallback(async () => {
        setLoading(true);

        try {
            const data = await apiRequest<Post[]>('/posts');
            setPosts(normalizePosts(data));
        } catch (error) {
            toast.error(
                error instanceof Error ? error.message : 'Falha ao carregar posts.',
            );
        } finally {
            setLoading(false);
        }
    }, []);

    useEffect(() => {
        void loadPosts();
    }, [loadPosts]);

    return (
        <>
            <Head title="Posts" />

            <div className="space-y-6">
                <div className="flex items-center justify-between gap-4">
                    <Heading
                        title="Posts"
                        description="Gerencie posts e campos adicionais por categoria."
                    />

                    <Button asChild>
                        <Link href="/posts/create">
                            <Plus className="size-4" />
                            Novo post
                        </Link>
                    </Button>
                </div>

                {loading ? (
                    <div className="flex justify-center py-12">
                        <Spinner className="size-6" />
                    </div>
                ) : posts.length === 0 ? (
                    <Card>
                        <CardContent className="py-10 text-center text-muted-foreground">
                            Nenhum post cadastrado.
                        </CardContent>
                    </Card>
                ) : (
                    <div className="grid gap-4">
                        {posts.map((post) => (
                            <Card key={post.uuid}>
                                <CardHeader className="flex flex-row items-start justify-between gap-4">
                                    <div className="space-y-1">
                                        <CardTitle>{post.title}</CardTitle>
                                        <p className="text-sm text-muted-foreground">
                                            {post.category?.name ?? post.category_uuid}
                                        </p>
                                    </div>

                                    <Button variant="outline" size="sm" asChild>
                                        <Link href={`/posts/${post.uuid}/edit`}>
                                            Editar
                                        </Link>
                                    </Button>
                                </CardHeader>

                                <CardContent className="space-y-2">
                                    <p className="text-sm">{post.description}</p>

                                    <AdditionalFieldsList
                                        fields={post.additional_fields ?? []}
                                    />
                                </CardContent>
                            </Card>
                        ))}
                    </div>
                )}
            </div>
        </>
    );
}

PostsIndex.layout = {
    breadcrumbs: [
        { title: 'Dashboard', href: '/dashboard' },
        { title: 'Posts', href: '/posts' },
    ],
};
