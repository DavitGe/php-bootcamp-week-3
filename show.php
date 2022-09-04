<!doctype html>
<html>
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
        if(mysqli_connect_errno()): ?>
            <div class="container mx-auto grid justify-items-center mt-12">
                <p class="decoration-2 text-red-600 bg-red-200 p-4 pt-2 pb-2 rounded-lg">Failed to connect with database.</p>
                <a class="underline" href="/">Go to home</a>
            </div>
            <?php else: ?>
                <?php
                //getting data
                $sql = 'SELECT name FROM students';
                $result = $conn->query($sql);
                $output = [];
                if($result->num_rows > 0) {
                    //check data of each row
                    while($row = $result->fetch_assoc()){
                        $decoded_row = json_decode(json_encode($row));
                        array_push($output, $decoded_row);
                    }
                }
            ?>
            <div class="mx-auto mt-12 grid justify-items-center">
                <h1 class="text-2xl text-center uppercase font-bold mb-4">List of stored mages</h1>
                <ul class="list-decimal grid justify-items-center">
                    <?php foreach($output as &$stud): ?>
                        <li class="text-red-600 text-xl">
                            <?= $stud->name ?>
                        </li>
                    <?php endforeach ?>
                </ul>
                <a href="/" class="text-blue-600 visited:text-purple-600 target:shadow-lg text-xl">Go to home</a>
            </div>
            <?php endif ?>
        </main>
    </body>
</html>