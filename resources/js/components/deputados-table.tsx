import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
    DialogTrigger
} from '@/components/ui/dialog';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Badge } from '@/components/ui/badge';
import { Eye, User } from 'lucide-react';
import { format } from 'date-fns';
import { ptBR } from 'date-fns/locale';
import { DataTable } from '@/components/data-table';

interface Deputado {
    id: number;
    id_deputado: number;
    nome: string;
    nome_eleitoral: string;
    siglaPartido: string | null;
    siglaUf: string;
    url_foto: string;
    email: string | null;
    situacao: string | null;
    gabinete: {
        telefone: string | null;
        email: string | null;
    } | null;
    total_despesas: number;
    quantidade_despesas: number;
    data_nascimento?: string;
    escolaridade?: string;
}

interface DeputadosTableProps {
    deputados: {
        data: Deputado[];
        current_page: number;
        last_page: number;
        total: number;
        per_page: number;
        path: string;
    };
}

export function DeputadosTable({ deputados }: DeputadosTableProps) {
    const formatCurrency = (value: number) => {
        return new Intl.NumberFormat('pt-BR', {
            style: 'currency',
            currency: 'BRL'
        }).format(value || 0);
    };

    const columns = [
        {
            key: 'foto',
            title: 'Foto',
            className: 'w-[80px]',
            cell: (deputado: Deputado) => (
                <Avatar className="h-10 w-10">
                    <AvatarImage src={deputado.url_foto} alt={deputado.nome} />
                    <AvatarFallback>
                        <User className="h-4 w-4" />
                    </AvatarFallback>
                </Avatar>
            )
        },
        {
            key: 'nome',
            title: 'Nome',
            cell: (deputado: Deputado) => (
                <div className="font-medium">
                    <div>{deputado.nome}</div>
                    <div className="text-sm text-muted-foreground">
                        {deputado.nome_eleitoral}
                    </div>
                </div>
            )
        },
        {
            key: 'partido',
            title: 'Partido/UF',
            cell: (deputado: Deputado) => (
                <Badge variant="secondary">
                    {(deputado.siglaPartido || 'Sem partido')}/{deputado.siglaUf}
                </Badge>
            )
        },
        {
            key: 'situacao',
            title: 'Situação',
            cell: (deputado: Deputado) => (
                <Badge variant="outline">{deputado.situacao || 'Não informado'}</Badge>
            )
        },
        {
            key: 'despesas',
            title: 'Despesas',
            className: 'text-right',
            cell: (deputado: Deputado) => (
                <div className="text-right">
                    {deputado.quantidade_despesas || 0}
                </div>
            )
        },
        {
            key: 'total',
            title: 'Total',
            className: 'text-right font-medium',
            cell: (deputado: Deputado) => (
                <div className="text-right font-medium">
                    {formatCurrency(deputado.total_despesas)}
                </div>
            )
        },
        {
            key: 'acoes',
            title: 'Ações',
            className: 'w-[100px]',
            cell: (deputado: Deputado) => (
                <Dialog>
                    <DialogTrigger asChild>
                        <Button variant="ghost" size="sm">
                            <Eye className="h-4 w-4" />
                        </Button>
                    </DialogTrigger>
                    <DialogContent
                        className="max-w-6xl w-11/12 max-h-[95vh] overflow-y-auto sm:w-10/12 lg:w-9/12 xl:w-8/12 2xl:w-7/12">
                        <DialogHeader>
                            <DialogTitle>Detalhes do Deputado</DialogTitle>
                            <DialogDescription>
                                Informações completas e despesas do deputado
                            </DialogDescription>
                        </DialogHeader>
                        <div className="grid gap-6 py-4">
                            <div className="flex items-center gap-4">
                                <Avatar className="h-20 w-20">
                                    <AvatarImage src={deputado.url_foto} alt={deputado.nome} />
                                    <AvatarFallback>
                                        <User className="h-8 w-8" />
                                    </AvatarFallback>
                                </Avatar>
                                <div>
                                    <h3 className="text-xl font-bold">{deputado.nome}</h3>
                                    <p className="text-muted-foreground">{deputado.nome_eleitoral}</p>
                                    <div
                                        className="flex gap-2 mt-2 flex-wrap">
                                        <Badge>{deputado.siglaPartido || 'Sem partido'}</Badge>
                                        <Badge>{deputado.siglaUf}</Badge>
                                        <Badge variant="outline">{deputado.situacao || 'Não informado'}</Badge>
                                    </div>
                                </div>
                            </div>

                            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <div>
                                    <h4 className="font-semibold mb-2">Contato</h4>
                                    <p className="break-words">
                                        Email: {deputado.email || 'Não informado'}
                                    </p>
                                    {deputado.gabinete && (
                                        <>
                                            <p className="break-words">
                                                Telefone: {deputado.gabinete.telefone || 'Não informado'}
                                            </p>
                                            <p className="break-words">
                                                Gabinete: {deputado.gabinete.email || 'Não informado'}
                                            </p>
                                        </>
                                    )}
                                </div>
                                <div>
                                    <h4 className="font-semibold mb-2">Informações Pessoais</h4>
                                    <p>Data de
                                        Nascimento: {deputado.data_nascimento ? format(new Date(deputado.data_nascimento), 'dd/MM/yyyy', { locale: ptBR }) : 'Não informado'}</p>
                                    <p>Escolaridade: {deputado.escolaridade || 'Não informado'}</p>
                                </div>
                                <div>
                                    <h4 className="font-semibold mb-2">Resumo Financeiro</h4>
                                    <p>Quantidade de Despesas: {deputado.quantidade_despesas || 0}</p>
                                    <p className="font-semibold">Total: {formatCurrency(deputado.total_despesas)}</p>
                                </div>
                            </div>
                        </div>
                    </DialogContent>
                </Dialog>
            )
        }
    ];

    return (
        <DataTable
            data={deputados.data}
            columns={columns}
            pagination={{
                currentPage: deputados.current_page,
                lastPage: deputados.last_page,
                totalCount: deputados.total,
                perPage: deputados.per_page,
                path: '/dashboard'
            }}
        />
    );
}
