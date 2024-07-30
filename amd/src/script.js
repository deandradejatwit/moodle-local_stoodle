export const init = () => {
    const flashcardSet = JSON.parse(document.querySelector(".flashcard-set").innerHTML);
    const card = document.querySelector(".js-card");
    const card1 = document.getElementById("card-answer");
    const card2 = document.getElementById("card-question");
    const arrKeys = Object.keys(flashcardSet);

    let currKeyElem = 0;
    let isAnswer = false;

    document.querySelector(".js-counter").innerText = "Flashcard No. " + (currKeyElem + 1);
    card2.innerHTML = flashcardSet[arrKeys[currKeyElem]].question;
    card1.innerHTML = flashcardSet[arrKeys[currKeyElem]].answer;

    // Clicking the flashcard itself
    card.addEventListener("click", () => {
        isAnswer = !isAnswer;
        card.classList.toggle('is-flipped');
        document.getElementById("card-answer").innerHTML = flashcardSet[arrKeys[currKeyElem]].answer;
    });

    // Previous Flashcard Button
    document.querySelector('.js-prev').addEventListener('click', () => {
        if (--currKeyElem < 0) {
            currKeyElem = arrKeys.length - 1;
        }
        checkAnswer(currKeyElem);
    });

    // Next Flashcard Button
    document.querySelector('.js-next').addEventListener('click', () => {
        if (++currKeyElem > arrKeys.length - 1) {
            currKeyElem = 0;
        }
        checkAnswer(currKeyElem);
    });

    /**
     * Swaps the question and answer on the flashcard
     *
     * @param {object} arrId
     */
    function checkAnswer(arrId) {
        if (isAnswer) {
            card.classList.toggle('is-flipped');
            isAnswer = false;
        }
        document.getElementById("card-question").innerHTML = flashcardSet[arrKeys[arrId]].question;
        document.querySelector(".js-counter").innerText = "Flashcard No. " + (currKeyElem + 1);
    }
};