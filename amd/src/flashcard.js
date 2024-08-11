export const init = () => {
    const dbFlashcardSet = JSON.parse(document.querySelector(".flashcard-set").innerHTML);
    const htmlCard = document.querySelector(".js-card");
    const nextButton = document.querySelector('.js-next');
    const previousButton = document.querySelector('.js-prev');
    const dbNames = Object.keys(dbFlashcardSet);

    let currFlashcardElem = 0;
    let onAnswerSide = false;
    validateFlashcard(currFlashcardElem);

    // Clicking the flashcard itself
    htmlCard.addEventListener("click", () => {
        onAnswerSide = !onAnswerSide;
        htmlCard.classList.toggle('is-flipped');
        document.getElementById("card-answer").innerHTML = dbFlashcardSet[dbNames[currFlashcardElem]].answer;
    });

    // Previous Flashcard Button
    previousButton.addEventListener('click', () => {
        if (--currFlashcardElem < 0) {
            currFlashcardElem = dbNames.length - 1;
        }
        validateFlashcard(currFlashcardElem);
    });

    // Next Flashcard Button
    nextButton.addEventListener('click', () => {
        if (++currFlashcardElem > dbNames.length - 1) {
            currFlashcardElem = 0;
        }
        validateFlashcard(currFlashcardElem);
    });

    /**
     * Sets flashcard to question side and displays a question at a specific element.
     *
     * @param {object} arrElem The flashcard element.
     */
    function validateFlashcard(arrElem) {
        if (onAnswerSide) {
            htmlCard.classList.toggle('is-flipped');
            onAnswerSide = false;
        }
        document.getElementById("card-question").innerHTML = dbFlashcardSet[dbNames[arrElem]].question;
        document.querySelector(".js-counter").innerText = "Flashcard No. " + (currFlashcardElem + 1);
    }
};