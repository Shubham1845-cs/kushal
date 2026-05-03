function getArrayValues() {
  const input = document.getElementById('arrayInput').value;
  return input
    .split(',')
    .map(item => item.trim())
    .filter(item => item !== '')
    .map(Number)
    .filter(item => !Number.isNaN(item));
}

function showResult(text) {
  document.getElementById('resultBox').textContent = text;
}

function showReverse() {
  const values = getArrayValues();
  showResult('Reversed: ' + values.reverse().join(', '));
}

function showAscending() {
  const values = getArrayValues();
  showResult('Ascending: ' + values.sort((a, b) => a - b).join(', '));
}

function showDescending() {
  const values = getArrayValues();
  showResult('Descending: ' + values.sort((a, b) => b - a).join(', '));
}

function searchValue() {
  const values = getArrayValues();
  const searchNumber = Number(document.getElementById('searchInput').value);
  const index = values.indexOf(searchNumber);

  if (index === -1) {
    showResult(searchNumber + ' not found in the array.');
  } else {
    showResult(searchNumber + ' found at position ' + (index + 1) + '.');
  }
}
