import { SVGAttributes } from 'react';

export default function AppLogoIcon(props: SVGAttributes<SVGElement>) {
    return (
        <img
            {...props}
            src="/imagens/marca-camara.svg"
            alt="Logo da Câmara dos Deputados"
        />
    );
}
