export const init = () => {
    const questionSet = JSON.parse(document.getElementById("question-set").innerHTML);
    const answerSet = JSON.parse(document.getElementById("answer-set").innerHTML);
    const questionDiv = document.querySelector(".js-answer-area");
    const scoreArea = document.querySelector(".score");
    const totalQuestions = Object.values(questionSet).length;

    const newSet = createFullSet(questionSet, answerSet);
    const typeSet = declareQuestionTypes(questionSet, answerSet);
    window.console.log(questionSet);
    window.console.log(answerSet);
    window.console.log([...newSet.entries()]);
    window.console.log(typeSet);
    window.console.log(newSet.get("q1")[0][0]);

    scoreArea.innerHTML = "Score: 0 / " + totalQuestions;
    document.querySelector(".submit-button").addEventListener('click', () => {
        questionValidation();
    });

    // Create questions and options
    for (const key in Object.values(questionSet)) {
        const newDiv = document.createElement("div");
        newDiv.id = "stoodle-div";
        const questionText = document.createElement("p");
        questionText.id = "stoodle-question-text";
        questionText.textContent = "Question " + (parseInt(key) + 1) + ": " + Object.values(questionSet)[key].question_text;
        newDiv.appendChild(questionText);

        // Check if it's a multiple choice or open-response question
        if (parseInt(Object.values(questionSet)[key].is_multiple_choice) === 0) {
            newDiv.id = "open-response";
            createOpenResponseQuestion(newDiv, ("option_" + Object.values(questionSet)[key].id));
        } else {
            newDiv.id = "multiple-choice";
            createMultipleChoiceQuestion(newDiv, Object.values(questionSet)[key].id, answerSet);
        }
        questionDiv.appendChild(newDiv);
    }



    /**
     * Creates a multiple choice question.
     *
     * @param {object} parent
     * @param {integer} questionId
     * @param {object} answerSet
     */
    function createMultipleChoiceQuestion(parent, questionId, answerSet) {
        for (const key in Object.values(answerSet)) {
            // Match answer id to question id
            const dbId = Object.values(answerSet)[key].stoodle_quiz_questionsid;
            if (dbId === questionId) {
                createInputNode(parent, ("option_" + questionId), (questionId), Object.values(answerSet)[key].option_text);
            }
        }
    }

    /**
     * Creates a open response question.
     *
     * @param {object} parent
     * @param {string} name
     */
    function createOpenResponseQuestion(parent, name) {
        const label = document.createElement("label");
        const textInput = document.createElement("textarea");
        textInput.id = name;
        textInput.cols = 100;
        label.appendChild(textInput);
        parent.appendChild(label);
    }

    /**
     * Creates a multiple choice question.
     *
     * @param {object} parent
     * @param {string} id
     * @param {string} name
     * @param {string} value
     */
    function createInputNode(parent, id, name, value) {
        // Create a radio input
        const radio = document.createElement("input");
        radio.type = "radio";
        radio.id = id;
        radio.name = name;
        radio.value = value;

        // Create the label to go with it
        const label = document.createElement("label");
        label.appendChild(radio);
        label.appendChild(document.createTextNode(value));

        // Append them to the parent
        parent.appendChild(label);
        parent.appendChild(document.createElement("br"));
    }

    /**
     * Validates the questions on the quiz. Checks if right or wrong.
     *
     */
    function questionValidation() {
        let numCorrect = 0;
        // const questionTypes = declareQuestionTypes(questionSet, answerSet);

        // for (const key in Object.values(questionSet)) {
        //     const questionText = "Question " + (parseInt(key) + 1) + ": " + Object.values(questionSet)[key].question_text;
        //     const question = Object.values(questionSet)[key].id;
        //     let option = null;

        //     if (questionTypes[key] === 0) {
        //         option = document.getElementById("option_" + question);
        //     } else if (questionTypes[key] === 1) {
        //         option = document.querySelector('input[name = "' + question + '"]:checked');
        //         if (checkMultipleChoice(answerSet, question, option)) {
        //             window.console.log("Question " + (parseInt(key) + 1) + " is correct");
        //             numCorrect++;
        //             parent.children[0].innerText = questionText + " \u{2705}";
        //         }
        //     }
        // }

        for (const key in Object.values(questionSet)) {
            const question = Object.values(questionSet)[key].id;
            const parent = document.getElementById("option_" + question).parentElement.parentElement;
            let option = null;

            if (parent.id === "multiple-choice") {
                option = document.querySelector('input[name = "' + question + '"]:checked');
            } else {
                option = document.getElementById("option_" + question);
            }

            if (option === null) {
                alert("Question " + (parseInt(key) + 1) + " is unanswered");
                return;
            }

            // Comparing them to the answers
            for (const answerKey in Object.values(answerSet)) {
                const questionText = "Question " + (parseInt(key) + 1) + ": " + Object.values(questionSet)[key].question_text;
                const answerText = Object.values(answerSet)[answerKey].option_text;
                const answerIsCorrect = parseInt(Object.values(answerSet)[answerKey].is_correct);
                if (answerText === option.value && answerIsCorrect === 1) {
                    window.console.log("Question " + (parseInt(key) + 1) + " is correct");
                    numCorrect++;
                    parent.children[0].innerText = questionText + " \u{2705}";
                    break;
                } else if (answerText === option.value && answerIsCorrect === 0) {
                    window.console.log("Question " + (parseInt(key) + 1) + " is wrong");
                    parent.children[0].innerText = questionText + " \u{274C}";
                    break;
                } else if (parent.id === "open-response") {
                    parent.children[0].innerText = questionText + " \u{274C} (manual review required)";
                }
            }
        }

        scoreArea.innerText = "Score: " + numCorrect + " / " + totalQuestions;
    }

    /**
     * Validates Multiple Choice questions.
     *
     * @param {Object} options
     * @param {integer} questionId
     * @param {Object} selectedOption
     * @return {boolean} True or false depending on if answer is correct.
     */
    function checkMultipleChoice(options, questionId, selectedOption) {
        for (const key in Object.values(options)) {
            if (Object.values(options)[key].stoodle_quiz_questionsid === questionId) {
                if (Object.values(options)[key].option_text === selectedOption.value && Object.values(options)[key].is_correct) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Returns an array with question types.
     * 0 - Open Response,
     * 1 - Multiple Choice,
     * 2 - Select All
     *
     * @param {Object} questions
     * @param {Object} options
     * @return {Array} Resulting array with question types.
     */
    function declareQuestionTypes(questions, options) {
        const questionTypesArray = new Array(Object.values(questions).length);
        for (const questionKey in Object.values(questions)) {
            const questionId = parseInt(Object.values(questions)[questionKey].id);
            let count = 0;
            for (const answerKey in Object.values(options)) {
                const answerObject = Object.values(options)[answerKey];
                if (parseInt(answerObject.stoodle_quiz_questionsid) === questionId && parseInt(answerObject.is_correct) === 1) {
                    count++;
                }
            }
            if (count > 1) {
                questionTypesArray[questionKey] = 2;
            } else if (count === 1 && parseInt(Object.values(questions)[questionKey].is_multiple_choice) === 1) {
                questionTypesArray[questionKey] = 1;
            } else {
                questionTypesArray[questionKey] = 0;
            }
        }
        return questionTypesArray;
    }


    /**
     * Return an array of questions and answers.
     *
     * @param {Object} qSet
     * @param {Object} oSet
     * @return {Map} Resulting array
     */
    function createFullSet(qSet, oSet) {
        let resultSet = new Map();
        for (const qKey in Object.values(qSet)) {
            let optionArray = [];
            let answerArray = [];
            for (const oKey in Object.values(oSet)) {
                if (Object.values(qSet)[qKey].question_number === Object.values(oSet)[oKey].stoodle_quiz_questionsid) {
                    optionArray.push(Object.values(oSet)[oKey].option_text);
                    if (parseInt(Object.values(oSet)[oKey].is_correct) === 1) {
                        answerArray.push(Object.values(oSet)[oKey].option_text);
                    }
                }
            }
            resultSet.set(Object.values(qSet)[qKey].question_text, [optionArray, answerArray]);
        }
        return resultSet;
    }
};
