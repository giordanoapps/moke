<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">

    <title>VMap</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">

    <!-- Place favicon.ico in the root directory -->

    <link rel="stylesheet" href="css/app.css">
  </head>
  <body>
    <!-- Use this installation button to install locally without going
         through the marketpace (see app.js) -->
    <!-- <button id="install-btn">Install</button>     -->

    <x-listview class="list">
      <header>
        <h1>VMap</h1>
       <!-- <button class="add" data-view=".edit" data-animation="slideDown">+</button>-->
      </header>
    </x-listview>

    <x-view class="detail">
      <header>
        <h1>Details</h1>
       <!-- <button data-view="x-view.edit">Edit</button>-->
      </header>

      <h1 class="title"></h1>
      <p class="desc"></p>
      <p class="date"></p>
    </x-view>

    <x-view class="edit">
      <header><h1>Edit</h1></header>
      <div class="field">Title: <input type="text" name="title" /></div>
      <div class="field">Description: <input type="text" name="desc" /></div>
      <button type="submit" class="add">Add</button>
    </x-view>

    <div class="loading">Loading...</div>

    <!-- Using require.js, a module system for javascript, include the
         js files. This loads "main.js", which in turn can load other
         files, all handled by require.js:
         http://requirejs.org/docs/api.html#jsfiles -->
    <script type="text/javascript"
            data-main="js/init.js"
            src="js/lib/require.js"></script>
  </body>
</html>
