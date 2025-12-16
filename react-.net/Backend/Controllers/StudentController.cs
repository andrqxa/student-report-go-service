using Microsoft.AspNetCore.Http;
using Microsoft.AspNetCore.Mvc;
using StudentBackEnd.Data;
using StudentBackEnd.Models.Dto;
using StudentBackEnd.Models.Entities;

namespace StudentBackEnd.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    public class StudentController : ControllerBase
    {
        private readonly ApplicationDbContext dbContext;
        public StudentController(ApplicationDbContext dbContext)
        {
            this.dbContext = dbContext;
        }

        [HttpGet]
        public IActionResult GetAllStudents()
        {
            var students = dbContext.Students.ToList();
            return Ok(students);
        }

        [HttpGet]
        [Route("{id:int}")]
        public IActionResult GetStudent(int id) {
            // write your code here
        }

        [HttpPost]
        public IActionResult AddStrudents(List<StudentDto> studentList)
        {
            // write your code here
        }

        [HttpPut]
        [Route("{id:int}")]
        public IActionResult UpdateStudent(int id, StudentDto studentDto)
        {
            // write your code here
        }

        [HttpDelete]
        [Route("{id:int}")]
        public IActionResult DeleteStudent(int id)
        {
            // write your code here
        }

        [HttpGet("search")]
        public IActionResult SearchByPhoneNumbr([FromQuery] string phoneNumber)
        {
            var students = dbContext.Students.Where(student => student.PhoneNumber.Contains(phoneNumber)).ToList();
            if (students.Count == 0)
            {
                return NotFound();
            }
            return Ok(students);
        }
    }
}
