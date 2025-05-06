<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>PHP Calculator with Keyboard</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #222;
      color: #fff;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    .calculator {
      background: #333;
      padding: 20px;
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
      width: 320px;
    }

    .display {
      background: #000;
      padding: 15px;
      font-size: 2rem;
      text-align: right;
      border-radius: 10px;
      margin-bottom: 15px;
      color: #0f0;
      height: 60px;
      overflow: hidden;
    }

    .buttons {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 10px;
    }

    .buttons input[type="submit"] {
      padding: 20px;
      font-size: 1.2rem;
      border: none;
      border-radius: 10px;
      background: #555;
      color: white;
      cursor: pointer;
      transition: background 0.3s;
    }

    .buttons input[type="submit"]:hover {
      background: #777;
    }

    .buttons input.equal {
      grid-column: span 2;
      background: #0a84ff;
    }

    .buttons input.clear {
      background: #ff3b30;
    }

    .buttons input.cancel {
      background: #ff9500;
    }

    input[type="text"] {
      position: absolute;
      opacity: 0;
      pointer-events: none;
    }
  </style>
</head>
<body>

<?php
  $display = "";

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['clear_all'])) {
      $display = "";
    } elseif (isset($_POST['cancel'])) {
      $display = substr($_POST['expression'], 0, -1);
    } elseif (isset($_POST['calculate'])) {
      $expression = $_POST['expression'];
      $expression = preg_replace('/[^0-9\+\-\*\/\.\(\)]/', '', $expression);
      try {
        @eval("\$display = $expression;");
      } catch (Throwable $e) {
        $display = "Error";
      }
    } elseif (isset($_POST['btn'])) {
      $display = $_POST['expression'] . $_POST['btn'];
    }
  }
?>

<div class="calculator">
  <form method="post" id="calc-form">
    <div class="display" id="display"><?php echo htmlspecialchars($display); ?></div>
    <input type="hidden" name="expression" id="expression" value="<?php echo htmlspecialchars($display); ?>">
    <input type="text" id="hidden-input" autofocus>

    <div class="buttons">
      <input type="submit" class="clear" name="clear_all" value="AC">
      <input type="submit" class="cancel" name="cancel" value="⌫">
      <input type="submit" name="btn" value="(">
      <input type="submit" name="btn" value=")">

      <input type="submit" name="btn" value="7">
      <input type="submit" name="btn" value="8">
      <input type="submit" name="btn" value="9">
      <input type="submit" name="btn" value="/">

      <input type="submit" name="btn" value="4">
      <input type="submit" name="btn" value="5">
      <input type="submit" name="btn" value="6">
      <input type="submit" name="btn" value="*">

      <input type="submit" name="btn" value="1">
      <input type="submit" name="btn" value="2">
      <input type="submit" name="btn" value="3">
      <input type="submit" name="btn" value="-">

      <input type="submit" name="btn" value="0">
      <input type="submit" name="btn" value=".">
      <input type="submit" class="equal" name="calculate" value="=">
      <input type="submit" name="btn" value="+">
    </div>
  </form>
</div>

<script>
  const form = document.getElementById('calc-form');
  const display = document.getElementById('display');
  const expressionInput = document.getElementById('expression');
  const hiddenInput = document.getElementById('hidden-input');

  // Focus input to capture keys
  hiddenInput.focus();
  window.addEventListener('click', () => hiddenInput.focus());

  document.addEventListener('keydown', function (e) {
    const key = e.key;

    if (!isNaN(key) || ['+', '-', '*', '/', '.', '(', ')'].includes(key)) {
      expressionInput.value += key;
      display.innerText = expressionInput.value;
    } else if (key === 'Enter') {
      e.preventDefault();
      const input = document.createElement('input');
      input.type = 'hidden';
      input.name = 'calculate';
      form.appendChild(input);
      form.submit();
    } else if (key === 'Backspace') {
      e.preventDefault();
      expressionInput.value = expressionInput.value.slice(0, -1);
      display.innerText = expressionInput.value;
    } else if (key.toLowerCase() === 'c') {
      expressionInput.value = '';
      display.innerText = '';
    }
  });
</script>

</body>
</html>
