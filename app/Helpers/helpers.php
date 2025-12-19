<?php

use Illuminate\Support\Facades\Validator;

// Início - Função para mascarar o e-mail
if (!function_exists('mascarar_email')) {
    /**
     * Mascara um email deixando parte visível e o restante com *
     *
     * Ex: joao@gmail.com -> jo*****@g*****.com
     */
    function mascarar_email($email)
    {
        $partes = explode('@', $email);

        if(count($partes) != 2) {
            return $email; // retorna original se não for email válido
        }

        $nome = $partes[0];
        $dominioCompleto = $partes[1];

        // Quebra domínio em nome e TLD
        $dominioPartes = explode('.', $dominioCompleto);
        $dominio = $dominioPartes[0];
        $tld = isset($dominioPartes[1]) ? '.' . $dominioPartes[1] : '';

        // Mascara nome (2 primeiros visíveis)
        $nomeMascarado = substr($nome, 0, 2) . str_repeat('*', max(0, strlen($nome) - 2));

        // Mascara domínio (1 primeiro visível)
        $dominioMascarado = substr($dominio, 0, 1) . str_repeat('*', max(0, strlen($dominio) - 1));

        return $nomeMascarado . '@' . $dominioMascarado . $tld;
    }
}
// Final - Função para mascarar o e-mail
// Início - Função para mostrar as duas primeiras letras do nome
if (!function_exists('iniciais_nome')) {
    function iniciais_nome(?string $nome): string
    {
        if (empty($nome)) {
            return '';
        }

        $nome = trim(preg_replace('/\s+/', ' ', $nome));
        $nome = mb_convert_encoding(strip_tags($nome), 'UTF-8', 'auto');

        $partes = explode(' ', $nome);
        $iniciais = '';
        if (count($partes) > 0) {
            $iniciais .= mb_substr($partes[0], 0, 1, 'UTF-8');
        }
        if (count($partes) > 1) {
            $iniciais .= mb_substr(end($partes), 0, 1, 'UTF-8');
        }

        return mb_strtoupper($iniciais, 'UTF-8');
    }
}
// Final - Função para mostrar as duas primeiras letras do nome
// Início - Função para formatar CPF/CNPJ
if(!function_exists('formatar_cpf_cnpj')) {
    function formatarCpfCnpj($documento) {
        $documento = preg_replace("/[^0-9]/", "", $documento);
    
        if (strlen($documento) === 11) {
            return vsprintf('%s%s%s.%s%s%s.%s%s%s-%s%s', str_split($documento));
        } elseif (strlen($documento) === 14) {
            return vsprintf('%s%s.%s%s%s.%s%s%s/%s%s%s%s-%s%s', str_split($documento));
        } else {
            return $documento;
        }
    }
}
// Final - Função para formatar CPF/CNPJ
// Início - Função para formatar Celular
if(!function_exists('formatar_celular')) {
    function formatarTelefone($numero) {
        $numeroLimpo = preg_replace("/[^0-9]/", "", $numero);

        $formatado = preg_replace("/^(\d{2})(\d{4,5})(\d{4})$/", "($1) $2-$3", $numeroLimpo);
    
        return $formatado;
    }
}
// Final - Função para formatar Celular
// Início - Função para formatar número 0800
if (!function_exists('formatar0800')) {
    function formatar0800($numero)
    {
        // Remove tudo que não for número
        $numeroLimpo = preg_replace("/[^0-9]/", "", $numero);

        // Se o número começar com 0800 e tiver 11 dígitos (0800 + 7 números)
        if (preg_match("/^0800(\d{3})(\d{4})$/", $numeroLimpo, $matches)) {
            return "0800 {$matches[1]} {$matches[2]}";
        }

        // Se tiver formato diferente, retorna o número original (sem quebrar layout)
        return $numero;
    }
}
// Final - Função para formatar número 0800
// Início - Função para formatar Etapa
if (!function_exists('formatarEtapa')) {
    function formatarEtapa(string $valor): string
    {
        return ucfirst(str_replace('_', ' ', $valor));
    }
}
// Final - Função para formatar Etapa