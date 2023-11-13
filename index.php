
<!DOCTYPE html>
<html>

<head>
    <title>Convertidor</title>
    <style>
        * {
            font-family: "Consolas";
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            margin-top: 20px;
        }

        form {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        input[type="text"] {
            padding: 10px;
            width: 70%;
            border: 1px solid #ccc;
            border-radius: 3px;
            margin-right: 10px;
        }

        button {
            padding: 10px 20px;
            background-color: #333;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #555;
        }

        .output {
            background-color: #f9f9f9;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 3px;
            margin-top: 1rem;


            border-radius: .5rem;

        }
    </style>
</head>

<body>
    <header>
        <h1 style="font-size: 24px;">Analizador</h1>
    </header> 
    <div class="container">
        <form method="POST">
            <div>
                <h4>Codigo en C</h4>
                <textarea name="texto" id="texto" cols="30" rows="5" placeholder="Ingrese el codigo" style="padding: 10px; width: 70%; border: 1px solid #ccc; border-radius: 3px; margin-right: 10px;"></textarea>
            </div>

            <div>
                <h4>Codigo en Java</h4>
                <textarea disabled name="texto" id="texto" cols="30" rows="5" placeholder="Ingrese el codigo" style="padding: 10px; width: 70%; border: 1px solid #ccc; border-radius: 3px; margin-right: 10px;"></textarea>
            </div>
  
        </div>
           
        </form>

   
      
    </div>

    <script>
        const textoInput = document.getElementById('texto');

        textoInput.addEventListener('input', () => {
            const texto = textoInput.value.replace(/\n/g, ' ');
            actualizarResultado(texto);
            console.log("hola")
        });

        function actualizarResultado(texto) {
         
            fetch('analizar.php', {
                    method: 'POST',
                    body: new URLSearchParams({
                        texto
                    }),
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                })
                .then(response => response.text())
                .then(resultados => {
                    console.log(resultados)
                    resultados = JSON.parse(resultados);
                    resultadoDiv.innerHTML = "Analisis Lexico";
                 
                    msj.innerHTML = "Analisis Sintactico";
                    if (lexCheckbox.checked) {
                        resultadoDiv.innerHTML = 'Valor&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Token <br>';
                        resultadoDiv.innerHTML += resultados.tokens.replace(/\n/g, '<br>').replace(/ /g, '&nbsp;')
                    }

                    if (sinCheckbox.checked) {
                        msj.innerHTML = resultados.msj.replace(/\n/g, '<br>').replace(/ /g, '&nbsp;')
                        if (resultados.msj.includes("syntax error")) {
                            msj.style.color = 'red';
                        } else {
                            msj.style.color = '';
                        }
                    }



                })
                .catch(error => {
                    console.error('Error en la solicitud AJAX:', error);
                });
        }
    </script>
</body>

</html>