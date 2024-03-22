let currentCategory = '';
let currentQuestionIndices = [];
let currentQuestionIndex = 0;
let score = 0;
let timeLeft = 30;
let timerInterval;
let timerSound = new Audio('Timer_Sound.mp3');
let backgroundMusic = new Audio('Background_Music.mp3');
var flag=0;

// Play background music when the page loads
function bgmusic() {
  if(flag==0){
  let backgroundMusic = new Audio('Background_Music.mp3');
  backgroundMusic.loop = true; // Loop the background music
  backgroundMusic.play();
    flag=1;
  }
}

function generateRandomIndices() {
  let indices = [];
  while (indices.length < 5) {
    let rand = Math.floor(Math.random() * 10) + 1;
    if (indices.indexOf(rand) === -1) indices.push(rand);
  }
  return indices;
}

function goToHome() {
  window.location.reload();
  backgroundMusic.play();
}

function startQuiz(category) {
  // Stop background music when the quiz starts
  backgroundMusic.pause();
  backgroundMusic.currentTime = 0;
  currentCategory = category;
  currentQuestionIndices = generateRandomIndices();
  currentQuestionIndex = 0;
  score = 0;
  document.getElementById("question-image").style.display = "block";
  document.getElementById("category-container").style.display = "none";
  document.getElementById("next-button").style.display = "block";
  document.getElementById("home-icon").style.display = "none";
  document.getElementById("history-icon").style.display = "none";
  document.getElementById("timer-text").style.display="block";
  displayQuestion();
  startTimer();
  startTimerSound();
}

async function displayQuestion() {
  const questionContainer = document.getElementById("question-container");
  questionContainer.style.transition = "opacity 0.3s ease";

  setTimeout(async () => {
    const imgExtensions = ["jpg", "png", "gif", "jfif", "jpeg"];
    let foundExtension;

    // Find the first existing image extension
    for (const extension of imgExtensions) {
      const imgSrc = `questions/${currentCategory}/question${currentQuestionIndices[currentQuestionIndex]}/question${currentQuestionIndices[currentQuestionIndex]}img.${extension}`;
      if (await imageExists(imgSrc)) {
        foundExtension = extension;
        break;
      }
    }

    if (foundExtension) {
      const imgSrc = `questions/${currentCategory}/question${currentQuestionIndices[currentQuestionIndex]}/question${currentQuestionIndices[currentQuestionIndex]}img.${foundExtension}`;
      document.getElementById("question-image").src = imgSrc;

      fetch(`questions/${currentCategory}/question${currentQuestionIndices[currentQuestionIndex]}/question${currentQuestionIndices[currentQuestionIndex]}opt.txt`)
        .then(response => response.text())
        .then(optionsText => {
          const allLines = optionsText.split('\n').filter(line => line.trim() !== '');
          const options = allLines.slice(0, -1);
          const correctAnswer = allLines[allLines.length - 1].trim();

          const optionsContainer = document.getElementById("options-container");
          optionsContainer.innerHTML = "";

          options.forEach((option, index) => {
            const optionButton = document.createElement("button");
            optionButton.textContent = option;
            optionButton.className = "option-button";

            optionButton.addEventListener("click", function() {
              checkAnswer(option, correctAnswer);
              optionButton.style.boxShadow = "0 0 10px 2px rgba(0, 0, 0, 0.5)";
            });

            optionsContainer.appendChild(optionButton);
          });

          questionContainer.style.opacity = 1;
        });
    } else {
      console.error("No valid image extension found.");
    }
  }, 200);
}

function checkAnswer(selectedOption, correctAnswer) {
  if (selectedOption.trim() === correctAnswer.trim()) {
    score++;
  }

  currentQuestionIndex++;

  if (currentQuestionIndex < 5) {
    displayQuestion();
  } else {
    endQuiz();
  }
}

function startTimer() {
  timerInterval = setInterval(function() {
    timeLeft--;

    document.getElementById("timer").textContent = timeLeft;

    if (timeLeft <= 0) {
      endQuiz();
    }
  }, 1000);
}

function startTimerSound() {
  timerSound.play();
}

function stopTimerSound() {
  timerSound.pause();
  timerSound.currentTime = 0;
}

function endQuiz() {
  clearInterval(timerInterval);
  stopTimerSound();
  backgroundMusic.play(); // Start background music again
  document.getElementById("home-icon").style.display = "block";
  document.getElementById("history-icon").style.display = "block";
  const scorePercentage = (score / 5) * 100;

  const questionContainer = document.getElementById("question-container");
  questionContainer.innerHTML = `
    <h2>Quiz Completed!</h2>
    <p>Your Score: ${score} out of 5</p>
    <p>Score Percentage: ${scorePercentage.toFixed(2)}%</p>
  `;

  const controlsContainer = document.getElementById("controls-container");
  controlsContainer.innerHTML = '';
}

function imageExists(image_url) {
  return fetch(image_url, { method: 'HEAD' })
    .then(res => {
      return res.ok;
    })
    .catch(err => {
      console.log('Error:', err);
      return false;
    });
}
