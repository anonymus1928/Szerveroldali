'use strict';
const {
  Model
} = require('sequelize');
module.exports = (sequelize, DataTypes) => {
  class Movie extends Model {
    /**
     * Helper method for defining associations.
     * This method is not a part of Sequelize lifecycle.
     * The `models/index` file will call this method automatically.
     */
    static associate(models) {
      // define association here
      this.belongsToMany(models.Genre, {
          through: 'GenreMovie',
          as: 'Genres'
      });
      this.hasMany(models.Rating);
    }
  };
  Movie.init({
    title: DataTypes.STRING,
    director: DataTypes.STRING,
    description: DataTypes.STRING,
    year: DataTypes.INTEGER,
    length: DataTypes.INTEGER,
    imageUrl: DataTypes.STRING,
    ratingsEnabled: DataTypes.BOOLEAN
  }, {
    sequelize,
    modelName: 'Movie',
  });
  return Movie;
};