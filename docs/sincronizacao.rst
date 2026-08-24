Sincronização SUAP → Moodle
==============================

Este guia explica o que acontece "por trás dos bastidores" no Moodle quando o serviço ``sync_up_enrolments``
(veja :doc:`api`) recebe uma estrutura de curso/turma vinda do SUAP — tanto para quem acompanha o resultado
prático (equipe pedagógica/coordenação) quanto para quem precisa rastrear o código (equipe de TI).

Resumo prático
------------------

Como colaborador em educação, o resultado prático que você vê no Moodle após a sincronização é:

1. **Salas organizadas**: pastas de campus, curso e semestre organizadas sem esforço manual.
2. **Salas prontas**: sala de diário e sala de coordenação de curso criadas automaticamente na categoria
   correspondente.
3. **Pessoas certas nos lugares certos**: alunos e professores inscritos com acesso ativo ou suspenso em
   sincronia com o SUAP Edu.
4. **Facilidade de gestão**: estudantes já divididos em grupos por polo ou período de ingresso dentro de cada
   disciplina.

Vocabulário
---------------

``SUAP``
   O ERP (Enterprise Resource Planning) construído pelo IFRN.

``SUAP Edu``
   Módulo do SUAP que funciona como Sistema de Gestão Acadêmica (SGA); é onde reside o registro acadêmico oficial
   (matrículas, notas oficiais, dados pessoais, vínculos). **Não é onde as aulas acontecem** — toda informação
   acadêmica oficial é gerida ali.

``Moodle``
   A plataforma de AVA (Ambiente Virtual de Aprendizagem / LMS) que hospeda as salas de aula virtuais do IFRN.
   **É onde ocorre o processo de ensino-aprendizagem.** Nenhuma informação acadêmica oficial é gerada diretamente
   aqui; ela é apenas refletida a partir do SUAP (inscrições) e só volta a ser oficial após retornar ao SUAP
   (notas).

``Integrador AVA``
   A ponte entre os dados do SUAP e o Moodle — o *middleware* que viabiliza esta integração, tipicamente o
   cliente HTTP que chama o serviço ``sync_up_enrolments`` deste plugin.

``Painel AVA``
   Interface que unifica, para o usuário, o acesso às salas de vários Moodle.

``Sincronizar`` / ``sincronização``
   Processo de cadastrar, alterar ou remover categoria, sala, usuário, inscrição, grupo ou vinculação a grupo,
   tanto de SUAP para Moodle quanto de Moodle de volta para SUAP (no caso de notas).

``Categoria``
   Equivale a uma *category* no Moodle.

``Sala``
   Equivale a um *course* no Moodle. Evita-se o termo "curso" aqui porque, na educação, "curso" já tem outro
   significado (o curso acadêmico do SUAP) e conflitaria com a terminologia institucional.

``Usuário``
   Equivale a um *user* no Moodle — normalmente em relação 1 para 1 com uma conta no SUAP.

``Docente``
   Professor formador, professor conteudista, professor principal, tutor ou mediador.

``Inscrição``
   Equivale a um *enrolment* associado a um ou mais *role assign* no Moodle. Um usuário pode ter várias
   inscrições/*role assign* em uma sala, especialmente educadores; de alunos espera-se apenas uma inscrição.

``Grupo``
   Equivale a um *group* no Moodle: uma forma de agrupar usuários dentro de uma sala para atividades coletivas.

``Agrupamento``
   Equivale a um *grouping* (grupo de grupos) no Moodle. A suíte não lida com este cenário.

``Coorte``
   Equivale a um *cohort* no Moodle: um grupo global de usuários (diferente do *grupo*, que é por sala), usado
   para inscrever/desinscrever automaticamente o usuário nas salas onde a coorte foi adicionada.

``Vinculação``
   Equivale a um *group member* no Moodle: o vínculo de um usuário a um grupo ou a uma coorte.

``Curso`` (no sentido do SUAP)
   Não tem equivalente direto no Moodle — o *course* do Moodle equivale à *Sala* definida acima. Cada *curso* do
   SUAP gera, no entanto, uma *categoria* no Moodle.

``Turma``
   Não tem equivalente no Moodle; cada *turma* gera uma *categoria*, e opcionalmente um *grupo* dentro da sala,
   com os alunos vinculados a ele.

``Polo``
   Não tem equivalente no Moodle; opcionalmente gera um *grupo*, com os alunos vinculados a ele.

``Programa``
   Não tem equivalente no Moodle; opcionalmente gera um *grupo*, com os alunos vinculados a ele.

``Disciplina`` / ``componente curricular``
   Não tem equivalente no Moodle — escopo de gestão acadêmica do SUAP. Não confundir com *Diário*.

``Média da etapa``
   Equivale à nota de uma categoria de notas no quadro de notas do Moodle, mapeada para ``N1``, ``N2``, ``N3`` ou
   ``N4`` (conforme o Projeto Político Pedagógico do curso), no campo ``idnumber`` da categoria de notas. Veja
   ``notes_to_sync`` em :doc:`instalacao`.

``Nota da avaliação final``
   Equivale à nota de uma categoria de notas mapeada para ``NAF``; só deve ser disponibilizada, no quadro de
   notas, para os alunos que precisaram ir para a atividade final de recuperação do diário.

``Média do diário`` / ``Média final do diário``
   Não têm equivalente no Moodle — são calculadas pelo próprio SUAP, independentemente de a nota vir do Moodle ou
   ser lançada manualmente pelo docente, conforme o PPC.

Quando a sincronização é executada
---------------------------------------

A sincronização é acionada por chamadas HTTP ao serviço ``sync_up_enrolments`` (veja :doc:`api`), tipicamente
disparadas:

* **por ações no SUAP** (por exemplo, ao confirmar uma oferta ou executar uma ação específica na Suite);
* **por agendamento de tarefas (cron)** configurado pela equipe de TI no lado do Integrador AVA, que roda
  periodicamente e mantém tudo atualizado.

Na prática, o colaborador de educação enxerga "rodadas" de sincronização ao longo do dia, sem precisar fazer
ajustes manuais no Moodle.

Cada chamada recebida é sempre persistida em ``suap_enrolment_to_sync`` e processada de duas formas possíveis
(veja :doc:`administracao` para a tela de acompanhamento):

* **síncrona** — quando o payload traz ``"sincrono": true``, todo o processamento (inscrições, grupos, suspensão)
  ocorre na própria requisição HTTP;
* **assíncrona** (padrão) — a requisição apenas garante a estrutura (categorias/curso/coortes) e devolve
  imediatamente a URL da sala; o restante (inscrições, grupos, suspensão de alunos, e a sincronização dos próprios
  alunos) é processado em segundo plano por uma tarefa *adhoc* (``sync_up_enrolments_task``).

Fluxo de sincronização no Moodle
-------------------------------------

Em cada sincronização, o Moodle passa **duas vezes** pelo fluxo de sala:

* uma vez para a **sala de coordenação** do curso;
* uma vez para a **sala de aula** dos estudantes (diário, autoinscrição, práticas ou modelo), conforme o tipo
  definido pelo campo ``sala_tipo``.

Em resumo, a sincronização faz três coisas principais:

* garante que a estrutura exista (categorias e salas);
* garante que as pessoas certas estejam nas salas, com o papel correto, em conformidade com o SUAP;
* organiza os estudantes em grupos, quando aplicável, para facilitar a gestão pedagógica.

.. image:: _static/fluxo-sincronizacao.png
   :alt: Fluxograma das 10 etapas da sincronização SUAP → Moodle, da sincronização de categorias até a suspensão
         de alunos que não vieram na sincronização.
   :width: 100%

O mesmo fluxo, na notação de origem (Mermaid) do diagrama acima:

.. code-block:: text

   flowchart TD
     A([Início]) --> B

     subgraph SISTEMA["Nível de sistema"]
       direction TD
       B[1. Sincroniza categorias] --> C[2. Sincroniza usuários]
       C --> D[3. Sincroniza coortes]
     end

     D --> F

     subgraph LACO["Laço: salas (coordenação e aula)"]
       direction TD
       F[4. Sincroniza sala] --> G[5. Vincula coorte à sala]
       G --> H{processando em background}
       H -->|sim| I[6. Instância o tipo de inscrição na sala]
       I --> J[7. Inscreve usuários na sala]
       J --> K[8. Sincroniza grupos]
       K --> L[9. Vincula ALUNOS aos respectivos grupos]
       L --> M{sala_tipo igual a diarios}
       M -->|sim| N[10. Suspende ALUNOS que não vieram na sincronização]
     end

     M -->|não| O([Fim da sincronização])
     N --> O

Passo a passo
------------------

1. Sincroniza categorias
~~~~~~~~~~~~~~~~~~~~~~~~~~~~

As categorias funcionam como pastas para manter as salas organizadas. A sincronização garante a seguinte
hierarquia padrão:

* **Pasta raiz (Diários)**: pasta principal que contém todos os diários (``idnumber``/``name`` configuráveis em
  ``top_category_idnumber``/``top_category_name``, veja :doc:`instalacao`).
* **Subpasta Campus**: ex. *Natal-Zona Leste*.
* **Subpasta Curso**: criada para o curso (ex. *Tecnologia em Sistemas para Internet*).
* **Subpasta Semestre**: organiza os diários por ano e período letivo (ex. *2026.1*).
* **Subpasta Turma**: pasta final contendo as salas específicas de uma turma (ex. *20261.1.011001.1P*).

Se alguma dessas pastas ainda não existir no Moodle, ela é criada automaticamente. Nenhuma delas é removida ou
renomeada em sincronizações futuras.

2. Sincroniza usuários
~~~~~~~~~~~~~~~~~~~~~~~~~~~

O Moodle verifica todos os usuários envolvidos na chamada (professores, equipe de apoio, coortes e, quando
processado em background, também os alunos):

* **Criação de novos usuários**: se um aluno acabou de se matricular ou um professor foi contratado, a conta é
  criada no Moodle, com senha aleatória e método de autenticação resolvido por ``auths_mapping``/``default_auth``.
  As preferências de ``default_user_preferences`` são aplicadas apenas nesta criação.
* **Atualização de dados**: alteração de e-mail, nome ou método de autenticação no SUAP é refletida no perfil do
  Moodle a cada sincronização.
* **Metadados do perfil**: informações como polo de apoio presencial, programa, modalidade do curso e campus são
  gravadas nos campos personalizados do perfil do usuário para relatórios posteriores.

3. Sincroniza coortes (grupos globais)
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

As coortes são grupos de usuários no nível do sistema Moodle (geralmente equipes pedagógicas, coordenação ou
apoio ao campus):

* o sistema cria ou atualiza as coortes no Moodle (ex. a coorte de colaboradores do curso);
* adiciona membros a essas coortes de acordo com a listagem recebida do SUAP (a remoção de membros que saíram da
  lista **não é feita** por esta etapa).

4. Sincroniza sala
~~~~~~~~~~~~~~~~~~~~~~~

Internamente, a Suite classifica a sala em um dos tipos abaixo (campo ``sala_tipo``, calculado por
``get_sala_tipo()``), de acordo com os dados recebidos:

.. list-table::
   :header-rows: 1
   :widths: 25 75

   * - ``sala_tipo``
     - Quando se aplica
   * - ``coordenacoes``
     - Sempre que a chamada é para a sala de coordenação de curso (metade do laço descrito acima).
   * - ``autoinscricoes``
     - Quando ``turma.restricoes`` vem preenchido.
   * - ``praticas``
     - Quando ``curso.praticas`` vem preenchido (na ausência de ``restricoes``).
   * - ``modelos``
     - Quando ``curso.modelos`` vem preenchido (na ausência dos casos acima).
   * - ``diarios``
     - Caso padrão, quando nenhum dos anteriores se aplica.

Durante esta etapa, o Moodle grava dezenas de campos customizados do curso (carga horária, tipo de disciplina,
turma, diário, se exige autoinscrição, se é sala de coordenação etc. — veja ``sync_course()`` em
``api/sync_up_enrolments.php``). Na criação, a sala nasce **oculta** (``visible = 0``) para que o professor possa
organizar o conteúdo antes de disponibilizá-la; em sincronizações seguintes a visibilidade não é mais alterada por
este passo.

5. Vincula coorte à sala
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

A coorte correspondente é associada à sala virtual via uma instância de inscrição do tipo ``cohort``. Na sala de
coordenação, a coorte costuma agrupar coordenadores, equipe pedagógica e outros colaboradores do curso; na sala de
aula, representa o conjunto de estudantes esperado naquela oferta.

6. Instancia o tipo de inscrição na sala
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

O Moodle garante que a sala tenha as instâncias de método de inscrição necessárias (normalmente ``manual``, uma
por combinação ``sala_tipo:papel_suap`` presente entre os usuários da chamada), conforme ``roles_mapping``. Se o
método ainda não existir na sala, ele é criado com as configurações padrão da Suite — o professor não precisa
configurar manualmente o tipo de inscrição no curso.

7. Inscreve usuários na sala
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Define quem pode acessar cada sala e com qual papel (estudante, professor, equipe etc.):

* **Inscrição de usuários**: professores, equipe e (em processamento assíncrono) alunos ativos são inscritos na
  sala correspondente, com o papel resolvido por ``roles_mapping``.
* **Atualização de status (ativo/suspenso)**: se a situação do estudante/servidor no SUAP for ``ativo``, o acesso
  é liberado/mantido; caso contrário (trancamento, cancelamento, desligamento), a inscrição é marcada como
  suspensa (``ENROL_USER_SUSPENDED``) — o usuário não perde o histórico de atividades já realizadas, mas deixa de
  acessar a sala.

8. Sincroniza grupos
~~~~~~~~~~~~~~~~~~~~~~~~~

Dentro da sala, os estudantes são subdivididos automaticamente conforme as opções ``course_group_*``/
``room_group_*`` (veja :doc:`instalacao`):

* **Grupo de Entrada**: agrupa pelo ano/semestre de ingresso (5 primeiros caracteres da matrícula do aluno, ex.
  *20251*).
* **Grupo de Turma**: agrupa pela sigla/código da turma no SUAP.
* **Grupo de Polo**: útil em cursos EaD — agrupa pelo polo de apoio presencial (ex. *Polo Macau*), ou
  ``--Sem polo--`` quando ausente.
* **Grupo de Programa**: agrupa pelo programa acadêmico (ex. *Institucional*, quando ausente).

9. Vincula alunos aos grupos
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

O Moodle verifica quem já está no grupo e adiciona apenas os alunos faltantes. Se um grupo ainda não existir na
sala, ele é criado nesta etapa.

.. note::
   No código-fonte atual, os passos 8 e 9 são implementados por uma única função (``sync_groups()``, que chama
   ``sync_group()`` internamente) em ``api/sync_up_enrolments.php`` — não existe uma função separada chamada
   ``sync_students_to_groups()``. A separação em dois passos aqui é conceitual (criar o grupo × vincular os
   alunos a ele).

10. Suspende alunos que não vieram na sincronização
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Este passo só acontece para salas do tipo ``diarios`` (``suspend_students_not_in_list_all_enrols()``).

A Suite compara a lista de estudantes enviada pelo SUAP com quem está inscrito, com papel de aluno, na sala. Se um
aluno estiver inscrito no Moodle mas não constar mais na lista oficial (cancelamento, trancamento, troca de turma
etc.), sua inscrição é suspensa automaticamente, preservando as atividades já realizadas, mas impedindo novo
acesso.

Observações
---------------

* Um usuário pode estar em vários grupos na mesma sala, mas isso tende a complicar o processo de
  ensino-aprendizagem — o estudante precisa escolher em qual grupo realizar cada atividade, o que aumenta a
  complexidade para docentes e estudantes e confunde a leitura de relatórios e notas. A Suite permite múltiplas
  vinculações, mas a recomendação padrão é manter uma configuração simples e clara.
* **Sincronização de preferências individuais**: além de estruturar cursos e matrículas, o plugin também permite a
  sincronização rápida de preferências de interface do usuário (favoritar um curso, expandir um menu etc.) entre o
  Moodle e o Painel AVA, através dos serviços ``set_user_preference`` e ``sync_user_preference`` — veja
  :doc:`api`.
* **Notas**: as notas oficiais continuam sendo do SUAP Edu (médias de etapa, de diário e final). O Moodle apenas
  devolve as notas das categorias configuradas em ``notes_to_sync`` (ex. ``N1``–``N4``, ``NAF``) através do
  serviço ``sync_down_grades`` — veja :doc:`api`. O cálculo da média do diário e da média final permanece sempre
  no SUAP.

Para a equipe de TI
------------------------

Esta seção mapeia as 10 etapas acima para a implementação em ``api/sync_up_enrolments.php``
(``sync_up_enrolments_service``).

**Orquestração geral** (método ``process()``):

.. code-block:: text

   sync_categories() → sync_users() → sync_cohorts()
     → para cada tipo de sala (coordenação, depois aula):
         sync_course() → sync_enrols_cohorts()
           → se síncrono/background:
               sync_enrols_manuals() → sync_enrolments() → sync_groups()
                 → se sala_tipo == 'diarios':
                     suspend_students_not_in_list_all_enrols()

**Mapeamento dos 10 passos para métodos**:

.. list-table::
   :header-rows: 1
   :widths: 8 30 62

   * - #
     - Método
     - O que faz
   * - 1
     - ``sync_categories()``
     - Cria/recupera a hierarquia de categorias (diários, campus, curso, semestre, turma) via
       ``core_course_category::create()``.
   * - 2
     - ``sync_users()`` / ``sync_user()`` / ``sync_profile_custom_fields()``
     - Cria/atualiza ``mdl_user`` (via ``user_create_user()``/``user_update_user()``) e os campos de perfil
       customizados (via ``profile_save_custom_fields()``).
   * - 3
     - ``sync_cohorts()``
     - Cria/atualiza coortes (``cohort_add_cohort()``/``cohort_update_cohort()``) e adiciona membros
       (``cohort_add_member()``).
   * - 4
     - ``sync_course($categoryid)``
     - Cria/atualiza o curso (``create_course()``/``update_course()``), define ``shortname``, ``idnumber``,
       visibilidade, ``enablecompletion`` e dezenas de ``customfield_*`` (incluindo ``sala_tipo``).
   * - 5
     - ``sync_enrols_cohorts()``
     - Garante uma instância de ``enrol_cohort`` ligando a coorte correta ao curso, com a role mapeada em
       ``coorte->role``.
   * - 6
     - ``sync_enrols_manuals()``
     - Resolve, por combinação ``sala_tipo:papel_suap``, a role e a instância de método de inscrição a usar
       (``roles_mapping``), criando a instância se necessário.
   * - 7
     - ``sync_enrolments()``
     - Cria/atualiza inscrições (``enrol_user()``/``update_user_enrol()``) conforme a situação do usuário no SUAP.
   * - 8–9
     - ``sync_groups()`` / ``sync_group()``
     - Cria/atualiza grupos (``groups_create_group()``) e vincula os alunos faltantes
       (``groups_add_member()``), por entrada, turma, polo e programa.
   * - 10
     - ``suspend_students_not_in_list_all_enrols()``
     - Apenas para ``sala_tipo == 'diarios'``: suspende (``ENROL_USER_SUSPENDED``) os alunos inscritos que não
       constam na lista de alunos sincronizados nesta chamada.

**Laço por tipo de sala**: o bloco de sala (passos 4 a 10) roda duas vezes por chamada de sincronização — uma para
a sala de coordenação e outra para a sala de aula (diário, autoinscrição, práticas ou modelos), conforme
``get_sala_tipo()``. A execução dos passos 6 a 10 (inscrições e grupos) só ocorre quando ``$this->inBackground``
é verdadeiro — ou seja, quando o payload trouxe ``"sincrono": true`` ou quando a chamada está rodando dentro da
tarefa *adhoc* ``sync_up_enrolments_task`` (veja :doc:`administracao`).

**Monitorando o processamento assíncrono** via linha de comando:

.. code-block:: bash

   ava exec moodle php admin/cli/scheduled_task.php --showdebugging --execute='\\local_suap\\task\\sync_up_enrolments_task'

.. note::
   Este comando dispara o *scheduler* de tarefas agendadas do Moodle, mas ``sync_up_enrolments_task`` é uma
   tarefa **adhoc** (``\core\task\adhoc_task``), não uma tarefa agendada por cron — na prática, ela é processada
   pelo executor de tarefas adhoc do Moodle (``admin/cli/adhoc_task.php``) sempre que houver itens pendentes na
   fila, não por este comando específico de tarefa agendada. Trate este trecho como um exemplo de comando de
   diagnóstico herdado da documentação original do plugin, não necessariamente como o comando correto para esta
   tarefa.

Áreas descritas como incompletas no README do repositório
------------------------------------------------------------------

O ``README.md`` deste repositório documenta ainda, como trabalho não finalizado, a **sincronização de notas** e a
**sincronização de faltas** ("Descrever mais.") — o serviço ``sync_down_grades`` (veja :doc:`api`) já existe e
cobre parte da sincronização de notas; a sincronização de faltas não tem endpoint implementado no código-fonte
atual (apenas o arquivo de exemplo vazio ``examples/sync_down_attendances_sample.json``).
