<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>InfoTech - Soluções em Tecnologia</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #f4f4f4;
            color: #333;
        }

        header {
            background-color: #1f3c88;
            color: white;
            padding: 20px 10%;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header h1 {
            font-size: 28px;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
        }

        nav a:hover {
            text-decoration: underline;
        }

        .banner {
            background-color: white;
            text-align: center;
            padding: 80px 20px;
        }

        .banner h2 {
            font-size: 36px;
            margin-bottom: 15px;
            color: #1f3c88;
        }

        .banner p {
            font-size: 18px;
            margin-bottom: 25px;
        }

        .botao {
            display: inline-block;
            background-color: #1f3c88;
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 5px;
        }

        .servicos {
            padding: 50px 10%;
            text-align: center;
        }

        .servicos h2 {
            margin-bottom: 30px;
            color: #1f3c88;
        }

        .cards {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .card {
            background-color: white;
            padding: 30px;
            width: 280px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .card h3 {
            color: #1f3c88;
            margin-bottom: 15px;
        }

        footer {
            background-color: #222;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 30px;
        }
    </style>
</head>

<body>

    <header>
        <h1>InfoTech</h1>

        <nav>
            <a href="#">Início</a>
            <a href="#servicos">Serviços</a>
            <a href="#contato">Contato</a>
        </nav>
    </header>

    <section class="banner">
        <h2>Soluções em Tecnologia</h2>

        <p>
            Tecnologia simples, eficiente e preparada para o seu negócio.
        </p>

        <a href="#servicos" class="botao">
            Conheça nossos serviços
        </a>
    </section>

    <section class="servicos" id="servicos">

        <h2>Nossos Serviços</h2>

        <div class="cards">

            <div class="card">
                <h3>Desenvolvimento</h3>
                <p>
                    Desenvolvimento de sistemas e aplicações para empresas.
                </p>
            </div>

            <div class="card">
                <h3>Consultoria</h3>
                <p>
                    Consultoria em tecnologia e soluções digitais.
                </p>
            </div>

            <div class="card">
                <h3>Suporte</h3>
                <p>
                    Suporte técnico e manutenção de sistemas.
                </p>
            </div>

        </div>

    </section>

    <footer id="contato">
        <p>&copy; 2026 InfoTech - Todos os direitos reservados.</p>
    </footer>

</body>
</html>