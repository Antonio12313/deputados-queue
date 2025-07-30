import { Button } from '@/components/ui/button';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import {
    Pagination,
    PaginationContent,
    PaginationEllipsis,
    PaginationItem,
    PaginationLink,
    PaginationNext,
    PaginationPrevious,
} from '@/components/ui/pagination';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Badge } from '@/components/ui/badge';
import { Eye, User } from 'lucide-react';
import { format } from 'date-fns';
import { ptBR } from 'date-fns/locale';

interface Gabinete {
    id: number;
    deputado_id: number;
    nome: string | null;
    predio: string | null;
    sala: string | null;
    andar: string | null;
    telefone: string | null;
    email: string | null;
    created_at: string | null;
    updated_at: string | null;
}

interface DeputadoData {
    id: number;
    id_deputado: number;
    nome: string;
    nome_eleitoral: string;
    siglaPartido: string | null;
    siglaUf: string;
    url_foto: string;
    email: string | null;
    situacao: string | null;
    gabinete: Gabinete | null;
    total_despesas: number;
    quantidade_despesas: number;
    data_nascimento: string | null;
    escolaridade: string | null;
}

interface DeputadoWrapper {
    data: DeputadoData;
}

interface PaginatedResponse<T> {
    current_page: number;
    data: T[];
    first_page_url: string;
    from: number;
    last_page: number;
    last_page_url: string;
    links: Array<{
        url: string | null;
        label: string;
        active: boolean;
    }>;
    next_page_url: string | null;
    path: string;
    per_page: number;
    prev_page_url: string | null;
    to: number;
    total: number;
}

interface DeputadosTableProps {
    deputados: PaginatedResponse<DeputadoWrapper> | null;
}

export function DeputadosTable({ deputados }: DeputadosTableProps) {
    if (!deputados || !deputados.data) {
        return (
            <div className="flex items-center justify-center h-32">
                <p className="text-muted-foreground">Carregando dados...</p>
            </div>
        );
    }

    const formatCurrency = (value: number) => {
        return new Intl.NumberFormat('pt-BR', {
            style: 'currency',
            currency: 'BRL',
        }).format(value || 0);
    };

    const formatDate = (dateString: string | null) => {
        if (!dateString) return 'Não informado';
        try {
            return format(new Date(dateString), 'dd/MM/yyyy', { locale: ptBR });
        } catch {
            return 'Data inválida';
        }
    };

    const generatePaginationLinks = () => {
        const currentPage = deputados.current_page;
        const lastPage = deputados.last_page;
        const delta = 2;
        const links = [];

        if (currentPage > 1) {
            links.push(
                <PaginationItem key="prev">
                    <PaginationPrevious href={`?page=${currentPage - 1}`} />
                </PaginationItem>
            );
        }

        if (currentPage > delta + 1) {
            links.push(
                <PaginationItem key={1}>
                    <PaginationLink href="?page=1">1</PaginationLink>
                </PaginationItem>
            );

            if (currentPage > delta + 2) {
                links.push(
                    <PaginationItem key="ellipsis-start">
                        <PaginationEllipsis />
                    </PaginationItem>
                );
            }
        }

        for (let i = Math.max(1, currentPage - delta); i <= Math.min(lastPage, currentPage + delta); i++) {
            links.push(
                <PaginationItem key={i}>
                    <PaginationLink
                        href={`?page=${i}`}
                        isActive={i === currentPage}
                    >
                        {i}
                    </PaginationLink>
                </PaginationItem>
            );
        }

        if (currentPage < lastPage - delta) {
            if (currentPage < lastPage - delta - 1) {
                links.push(
                    <PaginationItem key="ellipsis-end">
                        <PaginationEllipsis />
                    </PaginationItem>
                );
            }

            links.push(
                <PaginationItem key={lastPage}>
                    <PaginationLink href={`?page=${lastPage}`}>{lastPage}</PaginationLink>
                </PaginationItem>
            );
        }

        if (currentPage < lastPage) {
            links.push(
                <PaginationItem key="next">
                    <PaginationNext href={`?page=${currentPage + 1}`} />
                </PaginationItem>
            );
        }

        return links;
    };

    return (
        <div className="space-y-4">
            <div className="rounded-md border">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead className="w-[80px]">Foto</TableHead>
                            <TableHead>Nome</TableHead>
                            <TableHead>Partido/UF</TableHead>
                            <TableHead>Situação</TableHead>
                            <TableHead className="text-right">Despesas</TableHead>
                            <TableHead className="text-right">Total</TableHead>
                            <TableHead className="w-[100px]">Ações</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        {deputados.data.map((wrapper, index) => {
                            const deputado = wrapper.data;
                            return (
                                <TableRow key={`${deputado.id}-${index}`}>
                                    <TableCell>
                                        <Avatar className="h-10 w-10">
                                            <AvatarImage src={deputado.url_foto} alt={deputado.nome} />
                                            <AvatarFallback>
                                                <User className="h-4 w-4" />
                                            </AvatarFallback>
                                        </Avatar>
                                    </TableCell>
                                    <TableCell className="font-medium">
                                        <div>{deputado.nome}</div>
                                        <div className="text-sm text-muted-foreground">
                                            {deputado.nome_eleitoral}
                                        </div>
                                    </TableCell>
                                    <TableCell>
                                        <Badge variant="secondary">
                                            {(deputado.siglaPartido || 'Sem partido')}/{deputado.siglaUf}
                                        </Badge>
                                    </TableCell>
                                    <TableCell>
                                        <Badge variant="outline">{deputado.situacao || 'Não informado'}</Badge>
                                    </TableCell>
                                    <TableCell className="text-right">
                                        {deputado.quantidade_despesas || 0}
                                    </TableCell>
                                    <TableCell className="text-right font-medium">
                                        {formatCurrency(deputado.total_despesas)}
                                    </TableCell>
                                    <TableCell>
                                        <Dialog>
                                            <DialogTrigger asChild>
                                                <Button variant="ghost" size="sm">
                                                    <Eye className="h-4 w-4" />
                                                </Button>
                                            </DialogTrigger>
                                            <DialogContent className="max-w-4xl max-h-[80vh] overflow-y-auto">
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
                                                            <div className="flex gap-2 mt-2">
                                                                <Badge>{deputado.siglaPartido || 'Sem partido'}</Badge>
                                                                <Badge>{deputado.siglaUf}</Badge>
                                                                <Badge variant="outline">{deputado.situacao || 'Não informado'}</Badge>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                        <div>
                                                            <h4 className="font-semibold mb-2">Contato</h4>
                                                            <p className="break-words">Email: {deputado.email || 'Não informado'}</p>
                                                            {deputado.gabinete && (
                                                                <>
                                                                    <p className="break-words">Telefone: {deputado.gabinete.telefone || 'Não informado'}</p>
                                                                    <p className="break-words">Gabinete: {deputado.gabinete.email || 'Não informado'}</p>
                                                                </>
                                                            )}
                                                        </div>
                                                        <div>
                                                            <h4 className="font-semibold mb-2">Informações Pessoais</h4>
                                                            <p>Data de Nascimento: {formatDate(deputado.data_nascimento)}</p>
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
                                    </TableCell>
                                </TableRow>
                            );
                        })}
                    </TableBody>
                </Table>
            </div>

            {deputados.last_page > 1 && (
                <Pagination>
                    <PaginationContent>
                        {generatePaginationLinks()}
                    </PaginationContent>
                </Pagination>
            )}
        </div>
    );
}
