import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import { DeputadosTable } from '@/components/deputados-table';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Deputados',
        href: '/dashboard',
    },
];

export default function Dashboard({ deputados }: any) {
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

                <DeputadosTable deputados={deputados} />
            </div>
        </AppLayout>
    );
}
