const auth = require("./auth");
const db = require("../models");
const { faker } = require("@faker-js/faker");
const { Sequelize, sequelize } = db;
const { ValidationError, DatabaseError, Op } = Sequelize;
// TODO: Importáld a modelleket
const { Student, Exam, Task } = db;

module.exports = {
    Query: {
        // Elemi Hello World! példa:
        helloWorld: () => "Hello World!",

        // Példa paraméterezésre:
        helloName: (_, { name }) => `Hello ${name}!`,

        // TODO: Dolgozd ki a további resolver-eket a schema-val összhangban

        grade: async () => faker.number.int({ min: 1, max: 5 }),

        grades: async (_, {count}) => {
            const grades = [];
            for (let i = 0; i < count; i++) {
                grades.push(faker.number.int({ min: 1, max: 5 }));
            }
            return grades;
        },

        gradesFrom: async (_, {percentages}) => {
            const grades = [];
            for (const p of percentages) {
                if (p < 40) {
                    grades.push(1);
                } else {
                    grades.push(Math.min(2 + Math.floor((p - 40) / 15), 5));
                }

            }
            return grades;
        },

        students: async () => await Student.findAll(),

        exams: async () => await Exam.findAll(),

        exam: async (_, { id }) => await Exam.findByPk(id),

        studentByNeptun: async (_, { neptun }) => await Student.findOne({ where: { neptun } }),

        closestExam: async () => {
            return await Exam.findOne({
                where: {
                    startTime: {
                        [Op.gt]: Date.now()
                    }
                },
                order: [
                    ['startTime', 'ASC'],
                    ['id', 'ASC'],
                ]
            });
        },

        mostBusyStudent: async () => {
            const students = await Student.findAll({
                attributes: [
                    ['id', 'StudentId'],
                    [sequelize.fn('COUNT', sequelize.col('ExamId')), 'ExamCount']
                ],
                include: [{ model: Exam, attributes: [] }],
                group: 'StudentId',
                order: [
                    ['ExamCount', 'DESC'],
                    ['id', 'ASC'],
                ]
            });
            if(!students) {
                return null;
            }
            return await Student.findByPk(students[0].dataValues.StudentId);
        },
    },

    Student: {
        exams: async (student) => await student.getExams(),
    },

    Exam: {
        tasks: async (exam) => await exam.getTasks(),

        studentCount: async (exam) => await exam.countStudents(),

        maxScore: async (exam) => {
            const tasks = await exam.getTasks({ where: { extra: false } });
            return tasks.map(task => task.points).reduce((a, b) => a + b, 0);
        },

        perfectScore: async (exam) => {
            const tasks = await exam.getTasks();
            return tasks.map(task => task.points).reduce((a, b) => a + b, 0);
        },
    },

    Mutation: {
        createStudent: async (_, { input }) => await Student.create(input),

        registerStudents: async (_, { students, ExamId }) => {
            const exam = await Exam.findByPk(ExamId);
            if (!exam) {
                return null;
            }
            const result = {
                invalidNeptun: [],
                alreadyRegistered: [],
                justRegistered: [],
            };

            for (const neptun of students) {
                const student = await Student.findOne({ where: { neptun } });
                if(!student) {
                    result.invalidNeptun.push(neptun);
                } else {
                    if (await exam.hasStudent(student)) {
                        result.alreadyRegistered.push(neptun);
                    } else {
                        await exam.addStudent(student);
                        result.justRegistered.push(neptun);
                    }
                }
            }
            return result;
        },

        removePassiveStudents: async () => {
            const passiveStudents = await Student.findAll({
                where: { active: false },
                include: [{ model: Exam, through: { attributes: [] } }],
            });
            const results = [];
            for (const student of passiveStudents) {
                if (student.Exams.length > 0) {
                    const obj = { student, removedFromExams: [] };
                    for (const exam of student.Exams) {
                        obj.removedFromExams.push(exam);
                    }
                    results.push(obj);
                }
                await student.setExams([]);
            }
            return results;
        },
    }
};
