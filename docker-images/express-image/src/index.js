const Chance = require("chance");
const express = require("express");

const chance = new Chance();
const app = express();

app.get("/", function (req, res) {
  res.send(
    "Welcome to the lorem ipsum generator, visit /words for some words, /sentences for some sentences and /paragraphs for some paragraphs!"
  );
});

app.get("/words", function (req, res) {
  res.send(generateWords());
});

app.get("/sentences", function (req, res) {
  res.send(generateSentences());
});

app.get("/paragraphs", function (req, res) {
  res.send(generateParagraphs());
});

app.listen(3000, function () {
  console.log("Accepting HTTP requests on port 3000.");
});

console.log("Yo " + chance.name());

function generateWords() {
  const numberOfWords = chance.integer({
    min: 3,
    max: 12,
  });
  const words = [];
  for (let i = 0; i < numberOfWords; i++) {
    words.push(chance.word());
  }
  return words;
}

function generateSentences() {
  const numberOfSentences = chance.integer({
    min: 1,
    max: 5,
  });
  const sentences = [];
  for (let i = 0; i < numberOfSentences; i++) {
    sentences.push(chance.sentence());
  }
  return sentences;
}

function generateParagraphs() {
  const numberOfParagraphs = chance.integer({
    min: 1,
    max: 3,
  });
  const paragraphs = [];
  for (let i = 0; i < numberOfParagraphs; i++) {
    paragraphs.push(chance.paragraph());
  }

  return paragraphs;
}
