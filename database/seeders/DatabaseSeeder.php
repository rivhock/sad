<?php
namespace Database\Seeders;

use App\Enums\CategoriaServidor;
use App\Models\{
    User, Cargo, Lotacao, Secretaria,
    TipoAvaliacao, Formulario, ItemFormulario, CicloAvaliacao
};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Administrador','RH','Avaliador','Avaliado','COMPAQ'] as $papel) {
            Role::firstOrCreate(['name' => $papel, 'guard_name' => 'web']);
        }

        $admin = User::updateOrCreate(
            ['email' => 'admin@campobom.rs.gov.br'],
            [
                'nome' => 'Administrador',
                'cpf' => '00000000000',
                'matricula' => '000001',
                'password' => Hash::make(env('ADMIN_PASSWORD', 'mudar123')),
                'ativo' => true,
            ]
        );
        $admin->syncRoles(['Administrador']);

        $semSaude = Secretaria::firstOrCreate(['sigla' => 'SMS'], ['nome' => 'Secretaria Municipal de Saúde']);
        $semEdu   = Secretaria::firstOrCreate(['sigla' => 'SME'], ['nome' => 'Secretaria Municipal de Educação']);
        $semSeg   = Secretaria::firstOrCreate(['sigla' => 'SMSeg'], ['nome' => 'Secretaria Municipal de Segurança']);
        $semAdm   = Secretaria::firstOrCreate(['sigla' => 'SMAdm'], ['nome' => 'Secretaria Municipal de Administração']);

        foreach ([
            ['nome' => 'Enfermeiro',            'categoria' => CategoriaServidor::SAUDE],
            ['nome' => 'Professor',             'categoria' => CategoriaServidor::MAGISTERIO],
            ['nome' => 'Guarda Municipal',      'categoria' => CategoriaServidor::GUARDA],
            ['nome' => 'Agente Administrativo', 'categoria' => CategoriaServidor::QUADRO_GERAL],
        ] as $c) {
            Cargo::firstOrCreate(['nome' => $c['nome']], $c);
        }

        Lotacao::firstOrCreate(['nome' => 'UBS Centro'], ['secretaria_id' => $semSaude->id]);
        Lotacao::firstOrCreate(['nome' => 'EM Vila Nova'], ['secretaria_id' => $semEdu->id]);
        Lotacao::firstOrCreate(['nome' => 'Sede da Guarda'], ['secretaria_id' => $semSeg->id]);
        Lotacao::firstOrCreate(['nome' => 'Paço Municipal'], ['secretaria_id' => $semAdm->id]);

        TipoAvaliacao::firstOrCreate(['codigo' => 'estagio_probatorio'], ['nome' => 'Estágio Probatório']);
        TipoAvaliacao::firstOrCreate(['codigo' => 'desempenho'], ['nome' => 'Avaliação de Desempenho']);

        $this->criarFormulario(CategoriaServidor::SAUDE,        $this->itensSaude());
        $this->criarFormulario(CategoriaServidor::MAGISTERIO,   $this->itensMagisterio());
        $this->criarFormulario(CategoriaServidor::GUARDA,       $this->itensPlaceholder('Guarda Municipal'));
        $this->criarFormulario(CategoriaServidor::QUADRO_GERAL, $this->itensPlaceholder('Quadro Geral'));

        CicloAvaliacao::firstOrCreate(
            ['nome' => 'Ciclo ' . date('Y')],
            [
                'data_inicio' => now()->startOfYear(),
                'data_fim' => now()->endOfYear(),
                'ativo' => true,
            ]
        );

        $this->command->info('Seed concluído.');
    }

    private function criarFormulario(CategoriaServidor $cat, array $itens): void
    {
        $form = Formulario::firstOrCreate(
            ['titulo' => $cat->label(), 'versao' => 1],
            ['calculo' => 'soma', 'schema_json' => ['categoria' => $cat->value], 'ativo' => true]
        );

        foreach ($itens as $i => $desc) {
            ItemFormulario::updateOrCreate(
                ['formulario_id' => $form->id, 'ordem' => $i + 1],
                ['descricao' => $desc, 'peso' => 1, 'nota_min' => 0, 'nota_max' => 10]
            );
        }
    }

    private function itensPlaceholder(string $nome): array
    {
        return array_map(fn($i) => "[$nome] Item $i — preencher conforme anexo oficial", range(1, 10));
    }

    private function itensSaude(): array
    {
        return [
            'Assiduidade, pontualidade e registro funcional;',
            'Relacionamento interpessoal e capacidade de comunicação;',
            'Uso adequado de materiais e equipamentos, zelo ao patrimônio público;',
            'Apresenta profissionalismo, responsabilidade e comprometimento com o cargo e função pública que ocupa, agindo com descrição e sigilo na resolução de suas funções;',
            'Apresenta proatividade e habilidade de organização e planejamento para realizar as suas tarefas com o objetivo de alcançar objetivos comuns, preocupando-se com o atendimento de prazos e metas estabelecidas, adaptando-se sempre que necessário a novas demandas;',
            'Apresenta capacidade de relacionamento e interação com a equipe em que atua, mantendo um bom clima de trabalho, agindo na busca de resultados comuns;',
            'Respeita a hierarquia, normas vigentes e cumpre as determinações da chefia, coordenação e/ou supervisão imediata;',
            'Dialoga com os usuários, realizando atendimento das demandas da comunidade com atenção, cortesia e respeito, envolvendo-se no processo de trabalho como equipe, sendo comprometido para ser resolutivo, visando o melhor atendimento aos usuários;',
            'Possui conhecimento técnico necessário para desenvolver suas atividades como uso de sistemas informatizados, domínio de equipamentos e ferramentas disponíveis para o atendimento das demandas;',
            'Interessa-se por manter materiais de trabalho e documentos organizados e atualizados; bem como por seu aperfeiçoamento e capacitação dentro da sua área de atuação participando de cursos e capacitações.',
        ];
    }

    private function itensMagisterio(): array
    {
        return [
            'Assiduidade, pontualidade e registro funcional;',
            'Relacionamento interpessoal e capacidade de comunicação;',
            'Uso adequado de materiais e equipamentos, zelo ao patrimônio público;',
            'Demonstra comprometimento no desempenho de sua função, domínio de turma e resolve situações do cotidiano;',
            'Desenvolve práticas e metodologias com intervenção adequada às necessidades e às possibilidades dos alunos, inclusive NEE, com dificuldades de aprendizagem, respeitando-os na sua individualidade e considerando todos os aspectos do desenvolvimento durante a avaliação do processo de aprendizagem;',
            'Apresenta planejamento, em dia e organizado, adequado aos pressupostos do Documento Orientador Curricular Municipal e o PPP (Projeto Político Pedagógico) da escola em que atua;',
            'Demonstra profissionalismo, mantém ética e resguarda sua atuação, agindo com discrição e sigilo, observando os princípios que regem a Administração Pública e observando as atribuições do cargo;',
            'Dialoga com pais ou responsáveis, com atenção e cortesia, envolvendo-os no processo de ensino-aprendizagem, buscando o comprometimento para encontrar solução, visando ao melhor desenvolvimento da criança e do adolescente;',
            'Mantém materiais de trabalho, documentos e sistema da rede (chamadas, boletins, portfólios, acompanhamento) organizados e atualizados nos prazos estipulados;',
            'Participa das capacitações, formações continuadas, conselhos de classe, reuniões da escola (observando as combinações/decisões).',
        ];
    }
}
