import { Badge } from '@/components/ui/badge';
import type { PostAdditionalField } from '@/types/cms';

type Props = {
    fields: PostAdditionalField[];
};

export default function AdditionalFieldsList({ fields }: Props) {
    if (fields.length === 0) {
        return null;
    }

    return (
        <div className="space-y-2">
            <p className="text-xs font-medium text-muted-foreground">
                Campos adicionais
            </p>

            <div className="flex flex-wrap gap-2">
                {fields.map((field) => (
                    <Badge key={field.uuid} variant="secondary" className="font-normal">
                        <span className="font-medium">{field.label}:</span>{' '}
                        {field.value || '-'}
                    </Badge>
                ))}
            </div>
        </div>
    );
}
