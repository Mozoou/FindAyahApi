/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import "./styles/app.css";
import "flowbite/dist/flowbite.js";
import axios from "axios";

const startGame = document.querySelector("#settings_submit");

if (startGame) {

  startGame.addEventListener("click", async (e) => {
    // e.preventDefault();
    let questions = document.querySelector(".question-checkbox:checked");
    let chapters = document.querySelectorAll(".chapter-checkbox");

    console.log(questions)

    if (!questions.value) {
      alert('Please select a number of question !');
      return;
    }

    let chaptersSelected = [];

    chapters.forEach(chapter => {
      if (chapter.checked) {
        chaptersSelected.push(chapter.value)
      }
    });

    if (chaptersSelected.length === 0) {
      alert('Please select atleast 1 chapter !');
      return;
    }

    let gameQuestions = null;

    gameQuestions = await getQuestions(chaptersSelected, parseInt(questions.value));
  });
}

const getQuestions = async (chapterNumbers, numberOfQuestionsPerGame) => {
  return axios
    .post("/api/fetch_game_questions", JSON.stringify({ chapterNumbers, numberOfQuestionsPerGame }), {
      headers: {
        Accept: 'application/json',
        'Content-Type': 'application/json',
      },
    })
    .then((response) => {
      return response.data;
    })
    .catch((error) => {
      console.error(error);
      return Promise.reject(error);
    });
};
