<!DOCTYPE html>
<!-- Coding by CodingLab || www.codinglabweb.com -->
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Batu Gunting Kertas</title>
    <link rel="stylesheet" href="{{ asset('assets/css/games/batu.css') }}" />
  </head>
  <body>
    <section class="container">
      <div class="result_field">
        <div class="result_images">
          <span class="user_result">
            <img src="assets/images/rock.png" alt="" />
          </span>
          <span class="cpu_result">
            <img src="assets/images/rock.png" alt="" />
          </span>
        </div>
        <div class="result">Let's Play!!</div>
      </div>

      <div class="option_images">
        <span class="option_image">
          <img src="assets/images/rock.png" alt="" />
          <p>Batu</p>
        </span>
        <span class="option_image">
          <img src="assets/images/paper.png" alt="" />
          <p>Kertas</p>
        </span>
        <span class="option_image">
          <img src="assets/images/scissors.png" alt="" />
          <p>Gunting</p>
        </span>
      </div>
    </section>

    <script src="{{ asset('assets/js/games/batu.js') }}" defer></script>
  </body>
</html>
