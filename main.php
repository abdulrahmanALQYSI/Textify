<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Textify</title>
  <link rel="stylesheet" href="style.css">

  <meta name="author" content="Abdulrahman Alqaysi">
  <meta name="contributor" content="Amer Jehad">



  <meta name="description" content="This website is designed to help you effortlessly transform your text to suit your needs. For added convenience, we've created a mouseless experience, allowing you to navigate and control the website entirely using keyboard shortcuts.">

  <script>
    // Function to handle paste action
    function handlePaste() {
      if (navigator.clipboard && navigator.clipboard.readText) {
        // Use Clipboard API if available
        navigator.clipboard.readText().then((text) => {
          document.getElementById('inputText').value = text;
        }).catch((err) => {
          console.error('Failed to read clipboard text: ', err);
          alert('Failed to paste. Please manually paste your text.');
        });
      } else {
        // Fallback: Focus on the textarea and allow manual paste
        alert('Clipboard API not supported. Please manually paste your text.');
        document.getElementById('inputText').focus();
      }
    }

    // Function to select transformation option
    function selectOption(optionNumber) {
      const options = document.getElementsByName('transformOption');
      if (optionNumber >= 1 && optionNumber <= 3) {
        options[optionNumber - 1].checked = true; // Select the corresponding option
      }
    }

    // Add global keyboard shortcuts
    document.addEventListener('keydown', function(event) {
      // Cmd/Ctrl + V to paste into the textarea
      if ((event.ctrlKey || event.metaKey) && event.key === 'v') {
        event.preventDefault(); // Prevent default paste behavior
        handlePaste();
      }

      // Enter to submit the form
      if (event.key === 'Enter') {
        event.preventDefault(); // Prevent form submission if inside a textarea
        document.querySelector('form').submit();
      }

      // Ctrl + 1, Ctrl + 2, Ctrl + 3 to select transformation options
      if ((event.ctrlKey || event.metaKey) && event.key === '1') {
        event.preventDefault();
        selectOption(1); // Select UPPERCASE
      }
      if ((event.ctrlKey || event.metaKey) && event.key === '2') {
        event.preventDefault();
        selectOption(2); // Select lowercase
      }
      if ((event.ctrlKey || event.metaKey) && event.key === '3') {
        event.preventDefault();
        selectOption(3); // Select Capitalize Each Word
      }
    });

    // Focus on the textarea when the page loads
    window.addEventListener('load', function() {
      const inputText = document.getElementById('inputText');
      inputText.focus(); // Focus on the textarea
    });

    // Focus on the textarea when the page is shown (including when navigating back)
    window.addEventListener('pageshow', function() {
      const inputText = document.getElementById('inputText');
      inputText.focus(); // Focus on the textarea
    });
  </script>
</head>

<body>
  <?php
  session_start();
  $words = [];

  if (isset($_SESSION['email'])) {
    $conn = new mysqli("localhost", "textify", "textify123", "textify1");
    if (!$conn->connect_error) {
      $stmt = $conn->prepare("SELECT word FROM words WHERE email = ?");
      $stmt->bind_param("s", $_SESSION['email']);
      $stmt->execute();
      $result = $stmt->get_result();
      while ($row = $result->fetch_assoc()) {
        $words[] = htmlspecialchars($row['word']);
      }
      $stmt->close();
      $conn->close();
    }
  }
  ?>

  <img src="logo.png" alt="" width="320px">


  <br><br>
  <div style="display: flex; gap: 20px; justify-content: center; align-items: flex-start; flex-wrap: wrap;">
    <div class="container">
      <h2>This is the Word Wizard, harness its magic to transform your words into pure brilliance</h2>
      <?php if (isset($_SESSION['username'])): ?>
        <p style="margin-top: -15px; font-size: 32px; font-weight: bold; text-align: left; color: rgba(255, 255, 255, 0.95);">
          Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!
        </p>
      <?php endif; ?>
      <br>
      <form action="save_word.php" method="GET">
        <textarea id="inputText" name="inputText" rows="10" cols="50" placeholder="Enter your text here..." autofocus></textarea>
        <div class="button-group">
          <button type="button" onclick="handlePaste()">Paste</button>
          <span class="note">or Ctrl+V</span>
        </div>
        <div class="options">
          <div class="radio-option">
            <input type="radio" id="uppercase" name="transformOption" value="uppercase" checked>
            <label for="uppercase">UPPERCASE <span>(Ctrl+1)</span></label>
          </div>
          <div class="radio-option">
            <input type="radio" id="lowercase" name="transformOption" value="lowercase">
            <label for="lowercase">lowercase <span>(Ctrl+2)</span></label>
          </div>
          <div class="radio-option">
            <input type="radio" id="capitalize" name="transformOption" value="capitalize">
            <label for="capitalize">Capitalize Each Word <span>(Ctrl+3)</span></label>
          </div>
        </div>
        <br><br>
        <div class="button-group">
          <button type="submit">Transform Text</button>
          <span class="note">or Enter</span>
        </div>



      </form>

      <div class="button-group">
        <a href="logout.php">Log out</a>
      </div>
    </div>
    <div class="container" style="max-width: 300px; background: rgba(255,255,255,0.05); overflow-y: auto;">
      <h3>Your Submitted Words</h3>
      <input type="text" id="searchInput" placeholder="Search your words..." style="width: 100%; padding: 8px; margin-bottom: 10px; border-radius: 5px; border: 1px solid rgba(255,255,255,0.3); background: rgba(255,255,255,0.1); color: white;">
      <ul style="list-style-type: none; padding: 0; text-align: left;">
        <?php foreach ($words as $word): ?>
          <li style="padding: 5px 0; border-bottom: 1px solid rgba(255,255,255,0.2); display: flex; justify-content: space-between; align-items: center;">
            <span><?php echo $word; ?></span>
            <form method="POST" action="delete_word.php" style="margin: 0;">
              <input type="hidden" name="word" value="<?php echo htmlspecialchars($word); ?>">
              <button type="submit" style="margin-left: 10px; background: red; color: white; border: none; padding: 4px 8px; border-radius: 4px; cursor: pointer;">Delete</button>
            </form>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>



  <div class="footer-container">

    <p>Textify has been Developed by</p>
    <p style="font-size: 15px;color: white;">Abdulrahman Alqaysi</p>
    <p style="font-size: 15px;color: white;">Amer Jehad ghaith</p>

  </div>
  <script>
    document.getElementById('searchInput').addEventListener('input', function() {
      const filter = this.value.toLowerCase();
      const items = document.querySelectorAll('.container ul li');
      items.forEach(item => {
        const word = item.querySelector('span').textContent.toLowerCase();
        item.style.display = word.includes(filter) ? 'flex' : 'none';
      });
    });
  </script>
</body>

</html>