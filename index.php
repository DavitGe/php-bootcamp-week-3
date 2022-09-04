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
      <form method="POST" class="container mx-auto" action="find.php" enctype="multipart/form-data">
        <p class="uppercase text-xl mt-12">Enter name of personage: </p>   
        <div class="flex mt-2 align-middle"> 
          <label class="flex-1 relative block">
            <span class="sr-only">Search</span>
            <span class="absolute inset-y-0 left-0 flex items-center pl-2">
              <svg class="h-5 w-5 fill-slate-300" viewBox="0 0 20 20"><!-- ... --></svg>
            </span> 
            <input name="find" class="placeholder:italic placeholder:text-slate-400 block bg-white w-full border border-slate-300 rounded-md py-2 pl-9 pr-3 shadow-sm focus:outline-none focus:border-sky-500 focus:ring-sky-500 focus:ring-1 sm:text-sm" placeholder="Search..." type="text" name="search"/>
          </label> 
          <button type="submit" class="bg-black text-white ml-4 p-4 pt-2 pb-2 rounded-[12px]">Search for result</button>
        </div>
      </form>
      <div class="container mx-auto">
        <a href="/show.php" class="text-blue-600 visited:text-purple-600 target:shadow-lg text-xl">Show all from database</a>
      </div>
    </main>
	<script src="index.js"></script>
  </body>
</html>
