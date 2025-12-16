using Microsoft.EntityFrameworkCore;
using StudentBackEnd.Models.Entities;

namespace StudentBackEnd.Data
{
    public class ApplicationDbContext : DbContext
    {
        public ApplicationDbContext(DbContextOptions options) : base(options)
        {
        }
        public DbSet<Student> Students { get; set; }
    }
}
