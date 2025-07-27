import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/components/ui/table";
import { cn } from "@/lib/utils";
import {
    Pagination,
    PaginationContent,
    PaginationEllipsis,
    PaginationItem,
    PaginationLink,
    PaginationNext,
    PaginationPrevious
} from "@/components/ui/pagination";
import { ReactNode } from "react";

interface Column<T> {
    key: string;
    title: string;
    cell?: (item: T) => ReactNode;
    header?: () => ReactNode;
    className?: string;
}

interface DataTableProps<T> {
    data: T[];
    columns: Column<T>[];
    pagination?: {
        currentPage: number;
        lastPage: number;
        totalCount: number;
        perPage: number;
        path: string;
    };
    className?: string;
    preserveScroll?: boolean;
}

export function DataTable<T>({
                                 data,
                                 columns,
                                 pagination,
                                 className,
                             }: DataTableProps<T>) {
    const generatePaginationLinks = () => {
        if (!pagination) return null;

        const { currentPage, lastPage } = pagination;
        const delta = 2;
        const links = [];

        if (currentPage > 1) {
            links.push(
                <PaginationItem key="prev">
                    <PaginationPrevious href={`${pagination.path}?page=${currentPage - 1}`} />
                </PaginationItem>
            );
        }

        if (currentPage > delta + 1) {
            links.push(
                <PaginationItem key={1}>
                    <PaginationLink href={`${pagination.path}?page=1`}>1</PaginationLink>
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
                        href={`${pagination.path}?page=${i}`}
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
                    <PaginationLink href={`${pagination.path}?page=${lastPage}`}>
                        {lastPage}
                    </PaginationLink>
                </PaginationItem>
            );
        }

        if (currentPage < lastPage) {
            links.push(
                <PaginationItem key="next">
                    <PaginationNext href={`${pagination.path}?page=${currentPage + 1}`} />
                </PaginationItem>
            );
        }

        return links;
    };

    return (
        <div className={cn("space-y-4", className)}>
            <div className="rounded-md border">
                <Table>
                    <TableHeader>
                        <TableRow>
                            {columns.map((column) => (
                                <TableHead key={column.key} className={column.className}>
                                    {column.header ? column.header() : column.title}
                                </TableHead>
                            ))}
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        {data.map((item, rowIndex) => (
                            <TableRow key={rowIndex}>
                                {columns.map((column) => (
                                    <TableCell key={column.key} className={column.className}>
                                        {column.cell ? column.cell(item) : String((item as any)[column.key])}
                                    </TableCell>
                                ))}
                            </TableRow>
                        ))}
                    </TableBody>
                </Table>
            </div>

            {pagination && pagination.lastPage > 1 && (
                <Pagination>
                    <PaginationContent>
                        {generatePaginationLinks()}
                    </PaginationContent>
                </Pagination>
            )}
        </div>
    );
}
