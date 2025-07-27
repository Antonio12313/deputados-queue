import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/react';
import { DeputadosTable } from '@/components/deputados-table';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';

interface FilterOptions {
    partidos: string[];
    ufs: string[];
}

interface DashboardProps {
    deputados: any;
    filters: {
        search?: string;
        partido?: string;
        uf?: string;
    };
    filterOptions: FilterOptions;
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Deputados',
        href: '/dashboard'
    }
];

export default function Dashboard({ deputados, filters, filterOptions }: DashboardProps) {
    const { data, setData, get, reset } = useForm({
        search: filters?.search || '',
        partido: filters?.partido || '',
        uf: filters?.uf || ''
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        get(`/dashboard?search=${encodeURIComponent(data.search)}&partido=${encodeURIComponent(data.partido)}&uf=${encodeURIComponent(data.uf)}`);
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Deputados" />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-2xl font-bold">Deputados Federais</h1>
                        <p className="text-muted-foreground">
                            Lista de deputados e suas informações financeiras
                        </p>
                    </div>
                </div>

                <form onSubmit={handleSubmit} className="bg-card border rounded-lg p-4 space-y-4">
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div className="space-y-2">
                            <Label htmlFor="search">Nome</Label>
                            <Input
                                id="search"
                                placeholder="Buscar por nome..."
                                value={data.search}
                                onChange={(e) => setData('search', e.target.value)}
                            />
                        </div>

                        <div className="space-y-2">
                            <Label htmlFor="partido">Partido</Label>
                            <Select value={data.partido || '_all_'}
                                    onValueChange={(value) => setData('partido', value === '_all_' ? '' : value)}>
                                <SelectTrigger>
                                    <SelectValue placeholder="Selecione um partido" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="_all_">Todos</SelectItem>
                                    {filterOptions.partidos.map((partido) => (
                                        <SelectItem key={partido} value={partido}>
                                            {partido}
                                        </SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>
                        </div>

                        <div className="space-y-2">
                            <Label htmlFor="uf">UF</Label>
                            <Select value={data.uf || '_all_'}
                                    onValueChange={(value) => setData('uf', value === '_all_' ? '' : value)}>
                                <SelectTrigger>
                                    <SelectValue placeholder="Selecione um estado" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="_all_">Todos</SelectItem>
                                    {filterOptions.ufs.map((uf) => (
                                        <SelectItem key={uf} value={uf}>
                                            {uf}
                                        </SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>
                        </div>
                    </div>

                    <div className="flex gap-2">
                        <Button type="submit">Filtrar</Button>
                    </div>
                </form>

                <DeputadosTable deputados={deputados} />
            </div>
        </AppLayout>
    );
}
