module.exports = {
  testEnvironment: 'jsdom',
  transform: {
    '^.+\\.js$': 'babel-jest',
    '^.+\\.vue$': '@vue/vue3-jest',
  },
  moduleFileExtensions: ['js', 'json', 'vue'],

  // ⭐ Ajoute ceci pour résoudre "@"
  moduleNameMapper: {
    '^@/(.*)$': '<rootDir>/src/$1',
  },
}
