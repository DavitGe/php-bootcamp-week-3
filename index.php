<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>My Website</title>
    <script src="https://cdn.tailwindcss.com"></script>
  </head>
  <body>
    <main>
        <h1 class="text-3xl font-bold underline">
            Hello world!
        </h1>
        <?php 
            $opts = array(
                'http'=>array(
                'method'=>"GET",
                'header'=>'user-agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/101.0.4951.54 Safari/537.36'
                )
            );
            
            $context = stream_context_create($opts);

            $url = "http://hp-api.herokuapp.com/api/characters/students";
            $json = file_get_contents($url, false, $context);
            $data = json_decode($json);

            var_dump($data);

        ?>

    </main>
	<script src="index.js"></script>
  </body>
</html>
