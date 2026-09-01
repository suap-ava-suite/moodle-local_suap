# Dados para envio ao INPI

- Título: **local_suap (plugin Moodle do tipo local)**
- Linguagens de programação: `PHP`, `SQL`
- Classificação: `AP01 - Aplicativos`, `AT01 - Automação`, `CD01 - Comunicação de Dados`,
  `GI01 - Gerenciador de Informações`
- Data de criação: `04/10/2022`
- Apresentação: O local_suap é um plugin de integração entre o Ambiente Virtual de Aprendizagem Moodle e o Sistema
  Unificado de Administração Pública (SUAP) utilizado pelo IFRN. Ele automatiza operações administrativas dentro do
  Moodle a partir de dados acadêmicos e institucionais mantidos no SUAP, como criação e atualização de cursos, diários,
  turmas, usuários e vínculos, além de registrar metadados em campos de perfil. Dessa forma, atua como componente
  central da solução de sincronização entre SUAP e AVA Moodle.
- Descrição: O programa se destaca por oferecer integração profunda com o SUAP, explorando APIs e eventos do Moodle
  para automatizar tarefas críticas de gestão acadêmica, como sincronização de diários, usuários e perfis. Sua
  arquitetura baseada em tarefas agendadas e processamento assíncrono permite lidar com grandes volumes de dados,
  reduzindo falhas e evitando sobrecarga do ambiente de produção. Além disso, o plugin registra informações relevantes
  do curso e do polo em campos de perfil, viabilizando relatórios mais ricos e melhorando o acompanhamento de turmas e
  estudantes, com ganhos de desempenho, confiabilidade e rastreabilidade no fluxo SUAP–Moodle.
- Aplicação: O local_suap é aplicado em ambientes Moodle utilizados pelo IFRN para cursos regulares, presença,
  projetos, extensão e ofertas abertas. Sua principal função é receber instruções e dados do SUAP para criar e manter
  atualizados cursos, diários, matrículas e perfis de usuários, diminuindo a necessidade de cadastros manuais e
  garantindo alinhamento entre o registro acadêmico oficial e o AVA. O plugin também dá suporte a fluxos de
  sincronização de notas, faltas e modelos de disciplinas, conforme regras definidas por colegiados e setores
  acadêmicos, sustentando a operação cotidiana da educação presencial e a distância da instituição.
- Futuros: Entre os desenvolvimentos futuros previstos para o local_suap estão o aperfeiçoamento da sincronização
  assíncrona por meio de filas internas e tarefas agendadas, a ampliação do conjunto de dados trocados entre SUAP e
  Moodle e a melhoria das interfaces administrativas de configuração e monitoramento. Pretende-se consolidar dashboards
  para acompanhamento de diários, faltas e notas, bem como apoiar novos fluxos de integração definidos por projetos
  institucionais. Também se planeja aumentar a cobertura de testes, documentar cenários de uso avançados e adaptar o
  plugin a futuras mudanças na API do SUAP e nas versões do Moodle.
- Viabilidade Econômica: A tecnologia do local_suap é transferível para outras instituições que utilizem o SUAP como
  sistema acadêmico e o Moodle como AVA, como institutos federais, universidades e órgãos da administração pública. Ao
  automatizar criação de cursos, matrículas e sincronização de informações acadêmicas, o plugin reduz custos de
  secretaria, tempo de abertura de turmas e incidência de erros operacionais, beneficiando redes de educação básica,
  profissional, superior e de formação continuada. A solução também abre oportunidades de serviços de consultoria,
  implantação e suporte, possibilitando adaptações específicas a diferentes campi e contextos educacionais.
- Programas Similares: Programas similares incluem outros plugins de integração entre sistemas acadêmicos e Moodle,
  bem como soluções proprietárias que sincronizam dados de matrículas e notas. O local_suap diferencia-se por ser
  desenhado especificamente para o SUAP, explorando suas APIs e regras de negócio, e por estar alinhado às necessidades
  da rede federal de educação profissional e tecnológica. Sua aderência ao contexto do IFRN, somada ao uso de
  tecnologias abertas e à possibilidade de evolução colaborativa, o torna uma solução mais transparente, auditável e
  ajustada à realidade das instituições públicas que utilizam SUAP e Moodle de forma integrada.
