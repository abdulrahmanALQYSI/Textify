// Voice Recognition Feature
if ('webkitSpeechRecognition' in window || 'SpeechRecognition' in window) {
  const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
  const recognition = new SpeechRecognition();

  recognition.continuous = false;
  recognition.interimResults = false;
  recognition.lang = 'en-US';

  const inputText = document.getElementById('inputText');
  const voiceRecognitionButton = document.getElementById('voiceRecognitionButton');

  voiceRecognitionButton.addEventListener('click', () => {
    recognition.start();
    voiceRecognitionButton.innerHTML = '<i class="fas fa-microphone-slash"></i> Listening...';
    voiceRecognitionButton.disabled = true;
  });

  recognition.addEventListener('result', (event) => {
    const transcript = event.results[0][0].transcript;
    inputText.value = transcript;
  });

  recognition.addEventListener('end', () => {
    voiceRecognitionButton.innerHTML = '<i class="fas fa-microphone"></i> Speak';
    voiceRecognitionButton.disabled = false;
  });

  recognition.addEventListener('error', (event) => {
    console.error('Speech recognition error:', event.error);
    alert('Error: ' + event.error);
    voiceRecognitionButton.innerHTML = '<i class="fas fa-microphone"></i> Speak';
    voiceRecognitionButton.disabled = false;
  });

  // Keyboard shortcut for voice recognition (Ctrl + Space)
  document.addEventListener('keydown', (event) => {
    if ((event.ctrlKey || event.metaKey) && event.code === 'Space') {
      event.preventDefault();
      voiceRecognitionButton.click();
    }
  });
} else {
  console.warn('Your browser does not support the Web Speech API.');
  const voiceRecognitionButton = document.getElementById('voiceRecognitionButton');
  if (voiceRecognitionButton) {
    voiceRecognitionButton.disabled = true;
    voiceRecognitionButton.title = 'Voice recognition not supported in your browser.';
  }
}