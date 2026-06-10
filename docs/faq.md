# Perguntas frequentes sobre a sincronização SUAP → Moodle

## 1. O que é a sincronização entre SUAP e Moodle?

É o processo automático que garante que as salas virtuais no Moodle reflitam os dados acadêmicos oficiais que estão no
SUAP Edu (matrículas, ofertas, vínculos, etc.). Isso inclui criar/atualizar categorias, salas, usuários, coortes,
inscrições e grupos com base no que está registrado no SUAP.

## 2. Quando a sincronização é executada?

A sincronização pode ser acionada de duas formas principais:

- **Por ações no SUAP** (por exemplo, ao confirmar uma oferta ou executar uma ação específica na Suite).  
- **Por agendamento de tarefas** (cron) configurado pela equipe de TI, que roda periodicamente e mantém tudo atualizado.

Na prática, o colaborador de educação enxerga “rodadas” de sincronização ao longo do dia, sem precisar fazer ajustes
manuais no Moodle.

## 3. O que é criado automaticamente no Moodle?

De forma resumida, a sincronização cuida de:

- **Categorias**: hierarquia de pastas (Diários → Campus → Curso → Semestre → Turma).  
- **Salas (courses)**: diários, salas de coordenação, autoinscrição, práticas, modelos, de acordo com os dados do SUAP.  
- **Usuários (users)**: contas de estudantes, docentes e equipe de apoio, com dados pessoais e campos de perfil.  
- **Coortes**: grupos globais (equipes, conjuntos de estudantes, etc.).  
- **Inscrições**: quem entra em quais salas e com qual papel.  
- **Grupos**: subdivisões dentro da sala (turma, polo, programa, entrada).

## 4. Por que às vezes eu vejo duas “rodadas” de sincronização para a mesma oferta?

Porque em cada sincronização o Moodle percorre o fluxo da sala duas vezes:

- **Uma vez para a sala de coordenação do curso** (sala da equipe pedagógica/coordenação).  
- **Uma vez para a sala de aula dos estudantes** (diário, autoinscrição, práticas, modelos), conforme o tipo definido
pelo campo `sala_tipo`.

Isso garante que a equipe de coordenação e a sala dos ALUNOS fiquem sempre coerentes com o SUAP.

## 5. Quem é criado ou atualizado como usuário no Moodle?

Todos os perfis envolvidos na oferta:

- **Estudantes** matriculados no SUAP.  
- **Docentes** (professor formador, conteudista, principal, tutor, mediador).  
- **Equipe de apoio** definida pela instituição.

Quando há mudança de dados pessoais no SUAP (e-mail, nome, CPF, etc.), o perfil correspondente é atualizado no Moodle
na próxima sincronização.

## 6. Por que alguns alunos aparecem “suspensos” no Moodle?

Normalmente por dois motivos:

1. **Situação acadêmica no SUAP**:  
   - Trancamento, cancelamento, desligamento ou outra situação que impeça o acesso à sala.  
   - Nesses casos, a inscrição no Moodle é marcada como **Suspensa**, mas o histórico de atividades/notas é preservado.

2. **Não veio na sincronização (salas do tipo `diarios`)**:  
   - A Suite compara a lista oficial do SUAP com quem já está na sala.  
   - Se o ALUNO estiver no Moodle, mas **não constar mais** na lista enviada, a inscrição é suspensa automaticamente
   para manter a conformidade dos registros.

## 7. O professor pode perder acesso à sala por causa da sincronização?

Não nas mesmas regras dos estudantes.  
As regras de suspensão automática descritas para ALUNOS se aplicam a salas do tipo `diarios` e focam nas inscrições de
estudantes. A configuração de acesso de docentes e equipe segue a vinculação acadêmica e administrativa definida no
SUAP, e é mais estável.

## 8. O que são coortes e por que elas aparecem nas salas?

Coortes são **grupos globais** no Moodle, normalmente ligados a cursos, programas, equipes ou conjuntos específicos de
estudantes.Ao vincular uma coorte a uma sala:

- Quem entra nessa coorte passa a ter acesso à sala.  
- Quem sai da coorte tende a perder acesso (ou ser suspenso), dependendo da regra aplicada.

Isso facilita muito o gerenciamento de ofertas em grande escala, sem precisar inscrever usuário por usuário manualmente.

## 9. Como funcionam os grupos criados dentro das salas?

Os grupos organizam os estudantes para facilitar o trabalho pedagógico, de modo automático.  
Os tipos de grupos mais comuns são:

- **Grupo de Entrada**: agrupa por ano/semestre de ingresso.  
- **Grupo de Turma**: agrupa por código/sigla da turma no SUAP.  
- **Grupo de Polo**: agrupa por polo de apoio presencial (em EaD).  
- **Grupo de Programa**: agrupa por programa acadêmico.

Ao final da sincronização, os ALUNOS são vinculados aos grupos correspondentes de forma coerente com os dados do SUAP.

## 10. Posso ter um mesmo aluno em vários grupos da mesma sala?

Tecnicamente é possível, mas **não é recomendado** para a maioria dos cenários pedagógicos, porque:

- o aluno precisa escolher “em qual grupo” fazer cada atividade;  
- isso aumenta a complexidade para docentes e estudantes;  
- torna a leitura de relatórios e notas mais confusa.

A Suite até permite múltiplas vinculações, mas a recomendação padrão é manter uma configuração simples e clara.

## 11. E quanto às notas? O que vem do Moodle e o que fica no SUAP?

- As **notas oficiais** continuam sendo do SUAP Edu (médias de etapa, média de diário, média final, etc.).  
- O Moodle envia de volta as notas das **categorias de notas** configuradas (N1, N2, N3, N4, NAF, conforme o PPC e o
`idnumber` das categorias).
- A lógica de cálculo da média do diário e da média final permanece no SUAP, independentemente de as notas virem do
Moodle ou serem lançadas manualmente.

## 12. O que eu faço se “algo não bate” entre SUAP e Moodle?

Passos práticos:

1. Verifique **se a sincronização rodou** (cron, logs do integrador, horário da última execução).  
2. Confirme, no SUAP, se:
   - o aluno/docente está realmente vinculado à oferta correta;  
   - a turma/diário está ativa;  
   - não houve mudança recente de situação (trancamento, cancelamento, troca de turma).  
3. Confirme, no Moodle:
   - tipo de sala (`sala_tipo`) e categoria onde ela está;  
   - se a inscrição está ativa ou suspensa;  
   - em quais grupos o estudante foi inserido.

Se tudo estiver correto nos sistemas e o problema persistir, a equipe de TI pode consultar a seção “Para equipe de TI”
deste documento para rastrear o passo específico da sincronização (função responsável) e checar os logs da Suite.