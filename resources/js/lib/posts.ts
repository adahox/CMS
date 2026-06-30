import type {
    AdditionalFieldDefinition,
    AdditionalFieldValuePayload,
    ApiPost,
    Post,
    PostAdditionalField,
    PostPayload,
} from '@/types/cms';

function isRecord(value: unknown): value is Record<string, unknown> {
    return typeof value === 'object' && value !== null && !Array.isArray(value);
}

export function normalizeAdditionalField(raw: unknown): PostAdditionalField | null {
    if (!isRecord(raw)) {
        return null;
    }

    const uuid =
        typeof raw.uuid === 'string'
            ? raw.uuid
            : typeof raw.additional_field_uuid === 'string'
              ? raw.additional_field_uuid
              : '';

    if (!uuid) {
        return null;
    }

    return {
        uuid,
        label: typeof raw.label === 'string' ? raw.label : '',
        type: typeof raw.type === 'string' ? raw.type : 'text',
        options:
            raw.options === null || Array.isArray(raw.options) || isRecord(raw.options)
                ? (raw.options as PostAdditionalField['options'])
                : null,
        target: typeof raw.target === 'string' ? raw.target : undefined,
        value: typeof raw.value === 'string' ? raw.value : '',
    };
}

export function normalizePost(raw: unknown): Post {
    if (!isRecord(raw)) {
        return {
            uuid: '',
            title: '',
            description: '',
            category_uuid: '',
            additional_fields: [],
        };
    }

    const apiPost = raw as ApiPost;
    const rawFields = apiPost.additional_fields ?? apiPost.additionalFields ?? [];

    return {
        uuid: typeof apiPost.uuid === 'string' ? apiPost.uuid : '',
        title: typeof apiPost.title === 'string' ? apiPost.title : '',
        description:
            typeof apiPost.description === 'string' ? apiPost.description : '',
        category_uuid:
            typeof apiPost.category_uuid === 'string' ? apiPost.category_uuid : '',
        category: apiPost.category,
        additional_fields: Array.isArray(rawFields)
            ? rawFields
                  .map(normalizeAdditionalField)
                  .filter((field): field is PostAdditionalField => field !== null)
            : [],
    };
}

export function normalizePosts(raw: unknown): Post[] {
    if (!Array.isArray(raw)) {
        return [];
    }

    return raw.map(normalizePost);
}

export function buildPostPayload(
    title: string,
    description: string,
    categoryUuid: string,
    fields: AdditionalFieldDefinition[],
    values: Record<string, string>,
): PostPayload {
    return {
        title,
        description,
        category_uuid: categoryUuid,
        additional_field_values: fields.map((field) => ({
            additional_field_uuid: field.uuid,
            value: values[field.uuid] ?? '',
        })),
    };
}

export function mapAdditionalFieldsToValues(
    additionalFields: PostAdditionalField[] | undefined,
): Record<string, string> {
    if (!additionalFields) {
        return {};
    }

    return Object.fromEntries(
        additionalFields
            .filter((field) => field.uuid)
            .map((field) => [field.uuid, field.value ?? '']),
    );
}

export function mergeFieldValues(
    fields: AdditionalFieldDefinition[],
    values: Record<string, string>,
): Record<string, string> {
    return Object.fromEntries(
        fields.map((field) => [field.uuid, values[field.uuid] ?? '']),
    );
}

export function fieldOptions(field: AdditionalFieldDefinition): string[] {
    if (!field.options) {
        return [];
    }

    if (Array.isArray(field.options)) {
        return field.options;
    }

    if (Array.isArray(field.options.items)) {
        return field.options.items;
    }

    return [];
}

export function selectValue(value: string | undefined | null): string | undefined {
    return value ? value : undefined;
}

export function formatPostAdditionalFields(post: Post): string {
    return (post.additional_fields ?? [])
        .map((field) => `${field.label}: ${field.value ?? '-'}`)
        .join(' · ');
}

export type { AdditionalFieldValuePayload };
