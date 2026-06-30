import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { fieldOptions, selectValue } from '@/lib/posts';
import type { AdditionalFieldDefinition } from '@/types/cms';

type Props = {
    field: AdditionalFieldDefinition;
    value: string;
    onChange: (value: string) => void;
};

export default function AdditionalFieldInput({ field, value, onChange }: Props) {
    const options = fieldOptions(field);

    return (
        <div className="grid gap-2">
            <Label htmlFor={field.uuid}>{field.label}</Label>

            {field.type === 'select' && options.length > 0 ? (
                <Select value={selectValue(value)} onValueChange={onChange}>
                    <SelectTrigger id={field.uuid} className="w-full">
                        <SelectValue placeholder="Selecione" />
                    </SelectTrigger>
                    <SelectContent>
                        {options.map((option) => (
                            <SelectItem key={option} value={option}>
                                {option}
                            </SelectItem>
                        ))}
                    </SelectContent>
                </Select>
            ) : (
                <Input
                    id={field.uuid}
                    value={value}
                    onChange={(event) => onChange(event.target.value)}
                />
            )}
        </div>
    );
}
