export interface Gabinete {
    id: number;
    deputado_id: number;
    nome: string;
    predio: string;
    sala: string;
    andar?: string;
    telefone?: string;
    email?: string;
}

export interface RedeSocial {
    id: number;
    deputado_id: number;
    tipo: string;
    url: string;
}

export interface Deputado {
    id: number;
    id_deputado: number;
    nome: string;
    nome_eleitoral: string;
    siglaPartido: string;
    siglaUf: string;
    url_foto: string;
    email: string;
    situacao: string;
    gabinete: Gabinete;
    total_despesas: number;
    quantidade_despesas: number;
    data_nascimento: string;
    escolaridade: string;
}

export interface DeputadoDetalhado extends Omit<Deputado, 'data_nascimento'> {
    redesSociais: RedeSocial[];
    data_nascimento: string;
    municipio_nascimento?: string;
    uf_nascimento?: string;
}

export interface Despesa {
    id: number;
    deputado_id: number;
    ano: number;
    mes: number;
    tipo_despesa: string;
    cod_documento: string;
    tipo_documento: string;
    cod_tipo_documento: number;
    data_documento: string;
    numero_documento: string;
    valor_documento: number;
    url_documento: string;
    nome_fornecedor: string;
    cnpj_cpf_fornecedor: string;
    valor_liquido: number;
    valor_glosa: number;
    numero_ressarcimento: string;
    cod_lote: number;
    parcela: number;
}

export interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

export interface PaginatedResponse<T> {
    current_page: number;
    data: T[];
    first_page_url: string;
    from: number;
    last_page: number;
    last_page_url: string;
    links: PaginationLink[];
    next_page_url: string | null;
    path: string;
    per_page: number;
    prev_page_url: string | null;
    to: number;
    total: number;
}

export interface DashboardProps {
    deputados: PaginatedResponse<Deputado> | null;
    deputado_detalhes?: DeputadoDetalhado;
    despesas?: PaginatedResponse<Despesa>;
}
