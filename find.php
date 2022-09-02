<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Challange 3</title>
    <script src="https://cdn.tailwindcss.com"></script>
  </head>
  <body>
    <main>
        <?php 

        //mysql
        $serverName = "localhost";
        $userName = "root";
        $password = "admin";
        $dbName = "challange3";

        $conn = mysqli_connect($serverName, $userName, $password, $dbName);

        if(mysqli_connect_errno()){
            echo "Failed to connect!";
            exit();
        }else{

            //getting data
            $sql = 'SELECT name, id FROM students';
            $result = $conn->query($sql);
            echo "<br/>" . "=====================================" . "<br/>";

            var_dump($result);
            echo "<br/>" . "=====================================" . "<br/>";
            


            //options for getting content
                $opts = array(
                    'http'=>array(
                        'method'=>"GET",
                        'header'=>'user-agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/101.0.4951.54 Safari/537.36'
                        )
                    );
                    
                $context = stream_context_create($opts);
                    
                //getting data
                $url = "http://hp-api.herokuapp.com/api/characters/students";
                $json = file_get_contents($url, false, $context);
                $data = json_decode($json);

                //filtering by user input
                function test_studs($stud){
                    $name = strtolower($stud->name);
                    $name_inp = strtolower($_POST['find']);
                    print_r(str_contains($name, $name_inp));
                    return(str_contains($name, $name_inp));
                }

                //printing data
                print_r(array_filter($data, "test_studs"));
        }
        ?>
    </main>
	<script src="index.js"></script>
  </body>
</html>
