<?php
namespace App\Http\Controllers;

class ModeloCsvController extends Controller
{
    public function servidores()
    {
        $linhas = [
            // Cabeçalho
            ['nome', 'email', 'cpf', 'matricula', 'cargo', 'secretaria', 'lotacao', 'categoria', 'classe', 'data_admissao', 'data_fim_estagio'],

            // Exemplo com todos os campos
            ['João da Silva', 'joao.silva@campobom.rs.gov.br', '12345678901', '10001', 'Enfermeiro', 'SMS', 'UBS Centro', 'saude', 'A', '01/03/2020', '01/03/2023'],

            // Exemplo sem email (só nome)
            ['Maria Santos', '', '', '10002', 'Professor', 'SME', 'EM Vila Nova', 'magisterio', 'B', '15/07/2019', '15/07/2022'],

            // Exemplo com email vazio
            ['Carlos Lima', '', '34567890123', '10003', 'Guarda Municipal', 'SMSeg', 'Sede da Guarda', 'guarda', 'C', '10/01/2021', '10/01/2024'],

            // Exemplo com classe alta
            ['Ana Costa', 'ana.costa@campobom.rs.gov.br', '45678901234', '10004', 'Agente Administrativo', 'SMAdm', 'Paço Municipal', 'quadro_geral', 'D', '05/06/2018', '05/06/2021'],
        ];

        return $this->gerarCsv($linhas, 'modelo-servidores.csv');
    }

    protected function gerarCsv(array $linhas, string $nomeArquivo)
    {
        $handle = fopen('php://temp', 'r+');

        // BOM UTF-8 para o Excel abrir com acentos corretos
        fwrite($handle, "\xEF\xBB\xBF");

        // Usa ponto-e-vírgula (padrão Excel Brasil)
        foreach ($linhas as $linha) {
            fputcsv($handle, $linha, ';');
        }

        rewind($handle);
        $conteudo = stream_get_contents($handle);
        fclose($handle);

        return response($conteudo)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $nomeArquivo . '"');
    }
}
