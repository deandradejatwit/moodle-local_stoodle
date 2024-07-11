export const init = (someArray) => {
    const card = document.querySelector(".js-card");
    const card1 = document.getElementById("card-answer");
    const card2 = document.getElementById("card-question");
    const arrKeys = Object.keys(someArray);
    // for (let i = 0; i < arrKeys.length; i++) {
    //     window.console.log(someArray[arrKeys[i]].question);
    // }
    // window.console.log(someArray);

    let currKeyElem = 0;
    let isAnswer = false;

    card2.innerHTML = someArray[arrKeys[currKeyElem]].question;
    card1.innerHTML = someArray[arrKeys[currKeyElem]].answer;

    card.addEventListener("click", () => {
        isAnswer = !isAnswer;
        card.classList.toggle('is-flipped');
        document.getElementById("card-answer").innerHTML = someArray[arrKeys[currKeyElem]].answer;
    });

    document.querySelector('.js-prev').addEventListener('click', () => {
        if (--currKeyElem < 0) {
            currKeyElem = arrKeys.length - 1;
        }
        checkAnswer(currKeyElem);
    });

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
        document.getElementById("card-question").innerHTML = someArray[arrKeys[arrId]].question;
    }
};