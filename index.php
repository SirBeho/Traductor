
<!DOCTYPE html>
<html>

<head>
    <title>Convertidor</title>
    <style>
        * {
            font-family: "Consolas";
            box-sizing: border-box;
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
            max-width: 900px;
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
            gap: 5px;
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

        div{
            width: 100%;

        }
        textarea{
           padding: 10px; 
           width: 100%; 
            border: 1px solid #ccc; 
            border-radius: 3px;
            
        }
    </style>
</head>

<body>
    <header>
        <h1 style="font-size: 24px;">Analizador</h1>
    </header>
    <div class="container">
        <form >
            <div>
                <h4>Codigo en C</h4>
                <textarea name="textoC" id="textoC" rows="15" placeholder="Ingrese el código" ></textarea>
            </div>

            <div>
                <h4>Codigo en Java</h4>
                <textarea name="textoJava" id="textoJava"  rows="15" placeholder="Resultado en Java"  disabled></textarea>
            </div>
        </form>
    </div>

    <script>
        const textoCInput = document.getElementById('textoC');
        const textoJavaInput = document.getElementById('textoJava');

        textoCInput.addEventListener('input', () => {
            const texto = textoCInput.value;



            fetch('traductor.php', {
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
                    textoJavaInput.value = resultados;
                })
                .catch(error => {
                    console.error('Error en la solicitud AJAX:', error);
                });
    
        });
    </script>
</body>

</html>