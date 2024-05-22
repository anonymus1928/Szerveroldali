'use strict';
const {
  Model
} = require('sequelize');
module.exports = (sequelize, DataTypes) => {
  class Weather extends Model {
    /**
     * Helper method for defining associations.
     * This method is not a part of Sequelize lifecycle.
     * The `models/index` file will call this method automatically.
     */
    static associate(models) {
        this.belongsTo(models.Location);
        this.belongsToMany(models.Warning, { through: 'WarningWeather' });
    }
  }
  Weather.init({
    type: DataTypes.STRING,
    LocationId: DataTypes.INTEGER,
    temp: DataTypes.FLOAT,
    loggedAt: DataTypes.DATE
  }, {
    sequelize,
    modelName: 'Weather',
  });
  return Weather;
};
