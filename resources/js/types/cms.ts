export type ApiResponse<T> = {
    status: boolean;
    response: string;
    data: T;
    paginate: false;
};

export type Category = {
    uuid: string;
    name: string;
};

export type AdditionalFieldDefinition = {
    uuid: string;
    label: string;
    type: string;
    options: { items?: string[] } | string[] | null;
    target?: string;
};

export type PostAdditionalField = AdditionalFieldDefinition & {
    value?: string | null;
    additional_field_uuid?: string;
};

export type ApiPost = Post & {
    additionalFields?: PostAdditionalField[];
};

export type Post = {
    uuid: string;
    title: string;
    description: string;
    category_uuid: string;
    category?: Category;
    additional_fields?: PostAdditionalField[];
};

export type AdditionalFieldValuePayload = {
    additional_field_uuid: string;
    value: string;
};

export type PostPayload = {
    title: string;
    description: string;
    category_uuid: string;
    additional_field_values: AdditionalFieldValuePayload[];
};
