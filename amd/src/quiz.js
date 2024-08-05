export const init = () => {
    const questionSet = JSON.parse(document.getElementById("question-set").innerHTML);
    const answerSet = JSON.parse(document.getElementById("answer-set").innerHTML);
    const questionDiv = document.querySelector(".js-answer-area");
    const scoreArea = document.querySelector(".score");

    // Organize information for easier access
    const quizMap = createQuizHash(questionSet, answerSet);
    const questionTypes = declareQuestionTypes(questionSet, answerSet);

    scoreArea.innerHTML = "Score: 0 / " + quizMap.size;
    document.querySelector(".submit-button").addEventListener('click', () => {
        questionValidation();
    });

    // Create questions and answer options
    let index = 0;
    for (const [key, value] of quizMap.entries()) {
        const optionArray = value[0];

        // Set up div to house quiz question
        const newDiv = document.createElement("div");
        newDiv.id = "stoodle-div";
        const questionText = document.createElement("p");
        questionText.id = "stoodle-question-text";
        questionText.textContent = "Question " + (index + 1) + ": " + key;
        newDiv.appendChild(questionText);

        switch (questionTypes[index]) {
            default:
            case 0:
                newDiv.id = "open-response";
                createOpenResponseQuestion(newDiv, ("option_" + index));
                break;
            case 1:
                newDiv.id = "multiple-choice";
                for (const element in value[0]) {
                    createInputNode(newDiv, ("option_" + index), index, optionArray[element], "radio");
                }
                break;
            case 2:
                newDiv.id = "select-all";
                for (const element in value[0]) {
                    createInputNode(newDiv, ("option_" + index), index, optionArray[element], "checkbox");
                }
                break;
        }

        questionDiv.appendChild(newDiv);
        index++;
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
     * Creates an input using a given type.
     *
     * @param {object} parent The node for which the new input node should be parented to.
     * @param {string} id The id for the new input node.
     * @param {string} name The name for the input node.
     * @param {string} value The value for the new input node.
     * @param {string} type The type of the new input node.
     */
    function createInputNode(parent, id, name, value, type) {
        // Create a checkbox input
        const input = document.createElement("input");
        input.type = type;
        input.id = id;
        input.name = name;
        input.value = value;

        // Create the label to go with it
        const label = document.createElement("label");
        label.appendChild(input);
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
        const correctIcon = "\u{2705}";
        const incorrectIcon = "\u{274C}";
        let numCorrect = 0;
        let index = 0;

        // Check selected option against answer in the map
        for (const [key, value] of quizMap.entries()) {
            const answerArray = value[1];
            const htmlQuestionP = questionDiv.children[index].children[0];
            const questionText = "Question " + (index + 1) + ": " + key;
            let finalIcon = incorrectIcon;

            if (questionTypes[index] === 0) {
                // Check Open Response
                const option = document.getElementById("option_" + index);
                if (option.value === answerArray[0]) {
                    numCorrect++;
                    finalIcon = correctIcon;
                }
            } else if (questionTypes[index] === 1) {
                // Check Multiple Choice
                const option = document.querySelector('input[name= "' + index + '"]:checked');
                if (option === null) {
                    alert("Question " + (index + 1) + " is unanswered");
                    return;
                }
                if (answerArray[0] === option.value) {
                    numCorrect++;
                    finalIcon = correctIcon;
                }
            } else if (questionTypes[index] === 2) {
                // Check Select All
                const option = document.querySelectorAll('input[name="' + index + '"]:checked');
                if (option.length < 1) {
                    alert("Question " + (index + 1) + " is unanswered");
                    return;
                }
                let selectAllCorrectCounter = 0;
                for (let i = 0; i < answerArray.length && answerArray.length === option.length; i++) {
                    if (answerArray[i] === option[i].value) {
                        selectAllCorrectCounter++;
                    }
                }
                if (selectAllCorrectCounter === answerArray.length) {
                    numCorrect++;
                    finalIcon = correctIcon;
                }
            }
            htmlQuestionP.innerText = questionText + finalIcon;
            index++;
        }

        scoreArea.innerText = "Score: " + numCorrect + " / " + quizMap.size;
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
     * Creates a Map by matching questions to their options in their respective sets.
     * Consists of the question text as the key, and an array containing a set of all options and a set of correct options.
     *
     * @param {Object} qSet Set of all questions
     * @param {Object} oSet Set of all options
     * @return {Map} Resulting array
     */
    function createQuizHash(qSet, oSet) {
        let resultSet = new Map();
        for (const qKey in Object.values(qSet)) {
            let optionArray = [];
            let answerArray = [];
            for (const oKey in Object.values(oSet)) {
                const dbQuestionKey = parseInt(Object.values(qSet)[qKey].id);
                const dbOptionKey = parseInt(Object.values(oSet)[oKey].stoodle_quiz_questionsid);
                if (dbQuestionKey === dbOptionKey) {
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
