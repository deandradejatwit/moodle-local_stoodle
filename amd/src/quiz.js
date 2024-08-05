export const init = () => {
    const questionSet = JSON.parse(document.getElementById("question-set").innerHTML);
    const answerSet = JSON.parse(document.getElementById("answer-set").innerHTML);
    const questionDiv = document.querySelector(".js-answer-area");
    const scoreArea = document.querySelector(".score");
    const totalQuestions = Object.values(questionSet).length;

    const quizMap = createQuizHash(questionSet, answerSet);
    const questionTypes = declareQuestionTypes(questionSet, answerSet);

    scoreArea.innerHTML = "Score: 0 / " + totalQuestions;
    document.querySelector(".submit-button").addEventListener('click', () => {
        questionValidation();
    });

    let index = 0;
    for (const [key, value] of quizMap.entries()) {
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
                    createInputNodeRadio(newDiv, ("option_" + index), index, getOptionAtIndex(quizMap, key, element));
                }
                break;
            case 2:
                newDiv.id = "select-all";
                for (const element in value[0]) {
                    createInputNodeCheckBox(newDiv, ("option_" + index), index, getOptionAtIndex(quizMap, key, element));
                }
                break;
        }

        questionDiv.appendChild(newDiv);
        index++;
    }

    // Create questions and options
    // for (const key in Object.values(questionSet)) {
    //     const newDiv = document.createElement("div");
    //     newDiv.id = "stoodle-div";
    //     const questionText = document.createElement("p");
    //     questionText.id = "stoodle-question-text";
    //     questionText.textContent = "Question " + (parseInt(key) + 1) + ": " + Object.values(questionSet)[key].question_text;
    //     newDiv.appendChild(questionText);

    //     // TO-DO: Explain the purpose of this switch statement
    //     switch (questionTypes[key]) {
    //         default:
    //         case 0:
    //             newDiv.id = "open-response";
    //             createOpenResponseQuestion(newDiv, ("option_" + key));
    //             break;
    //         case 1:
    //             newDiv.id = "multiple-choice";
    //             for (const element in quizMap.get(Object.values(questionSet)[key].question_text)[0]) {
    //                 const optionText = quizMap.get(Object.values(questionSet)[key].question_text)[0][element];
    //                 createInputNodeRadio(newDiv, ("option_" + key), key, optionText);
    //             }
    //             break;
    //         case 2:
    //             newDiv.id = "select-all";
    //             for (const element in quizMap.get(Object.values(questionSet)[key].question_text)[0]) {
    //                 const optionText = quizMap.get(Object.values(questionSet)[key].question_text)[0][element];
    //                 createInputNodeCheckBox(newDiv, ("option_" + key), key, optionText);
    //             }
    //             break;
    //     }

    //     questionDiv.appendChild(newDiv);
    // }

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
     * Creates a radio button.
     *
     * @param {object} parent
     * @param {string} id
     * @param {string} name
     * @param {string} value
     */
    function createInputNodeRadio(parent, id, name, value) {
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
     * Creates a checkbox button.
     *
     * @param {object} parent
     * @param {string} id
     * @param {string} name
     * @param {string} value
     */
    function createInputNodeCheckBox(parent, id, name, value) {
        // Create a radio input
        const checkbox = document.createElement("input");
        checkbox.type = "checkbox";
        checkbox.id = id;
        checkbox.name = name;
        checkbox.value = value;

        // Create the label to go with it
        const label = document.createElement("label");
        label.appendChild(checkbox);
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

        // TO-DO: Explain the purpose of this for-loop
        for (const [key, value] of quizMap.entries()) {
            const questionText = "Question " + (index + 1) + ": " + key;

            if (questionTypes[index] === 0) {
                // Check Open Response
                const option = document.getElementById("option_" + index);
                if (option.value === getAnswerAtIndex(quizMap, key, 0)) {
                    window.console.log("Question " + (index + 1) + " is correct");
                    numCorrect++;
                    questionDiv.children[index].children[0].innerText = questionText + " " + correctIcon;
                } else {
                    questionDiv.children[index].children[0].innerText = questionText + incorrectIcon + " (manual review required)";
                }
            } else if (questionTypes[index] === 1) {
                // Check Multiple Choice
                const option = document.querySelector('input[name= "' + index + '"]:checked');
                if (option === null) {
                    alert("Question " + (index + 1) + " is unanswered");
                    return;
                }
                if (getAnswerAtIndex(quizMap, key, 0) === option.value) {
                    window.console.log("Question " + (index + 1) + " is correct");
                    numCorrect++;
                    questionDiv.children[index].children[0].innerText = questionText + " " + correctIcon;
                } else {
                    questionDiv.children[index].children[0].innerText = questionText + " " + incorrectIcon;
                }
            } else if (questionTypes[index] === 2) {
                // Check Select All
                const option = document.querySelectorAll('input[name="' + index + '"]:checked');
                if (option.length < 1) {
                    alert("Question " + (index + 1) + " is unanswered");
                    return;
                }
                let selectAllCorrectCounter = 0;
                if (value[1].length !== option.length) {
                    questionDiv.children[index].children[0].innerText = questionText + " " + incorrectIcon;
                    continue;
                }
                for (let i = 0; i < value[1].length; i++) {
                    if (getAnswerAtIndex(quizMap, key, i) === option[i].value) {
                        selectAllCorrectCounter++;
                    }
                }
                if (selectAllCorrectCounter === value[1].length) {
                    numCorrect++;
                    window.console.log("Question " + (index + 1) + " is correct");
                    questionDiv.children[index].children[0].innerText = questionText + " " + correctIcon;
                } else {
                    questionDiv.children[index].children[0].innerText = questionText + " " + incorrectIcon;
                }
            }
            index++;
        }

        scoreArea.innerText = "Score: " + numCorrect + " / " + totalQuestions;
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

    /**
     * Getter for the array of all answer options in the quizMap.
     *
     * @param {Map} map The quiz map.
     * @param {string} question The key for the map.
     * @param {number} index The index of the option array.
     */
    function getOptionAtIndex(map, question, index) {
        return map.get(question)[0][index];
    }

    /**
     * Getter for the array of all answers in the quizMap.
     *
     * @param {Map} map The quiz map.
     * @param {string} question The key for the map.
     * @param {number} index The index of the option array.
     */
    function getAnswerAtIndex(map, question, index) {
        return map.get(question)[1][index];
    }
};
