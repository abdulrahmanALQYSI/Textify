// Get the query parameters from the URL
const urlParams = new URLSearchParams(window.location.search);
const inputText = urlParams.get('inputText');
const transformOption = urlParams.get('transformOption');

// Function to transform the text
function transformText(text, option) {
  switch (option) {
    case 'uppercase':
      return text.toUpperCase();
    case 'lowercase':
      return text.toLowerCase();
    case 'capitalize':
      return text.replace(/\b\w/g, (char) => char.toUpperCase());
    default:
      return text;
  }
}

// Display the transformed text on the page
const resultTextElement = document.getElementById('resultText');
if (inputText && transformOption) {
  const transformedText = transformText(inputText, transformOption);
  resultTextElement.textContent = transformedText;
} else {
  resultTextElement.textContent = 'No text to transform.';
}

