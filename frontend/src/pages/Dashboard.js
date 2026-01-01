import React, { useState, useEffect } from 'react';
import {
  Container,
  Typography,
  Grid,
  Card,
  CardContent,
  Box,
  Alert,
  CircularProgress,
  Button,

} from '@mui/material';
import {
  Business as DepartmentIcon,
  Class as ClassIcon,
  School as TeacherIcon,
  MenuBook as CourseIcon,
  People as StudentIcon,
  ArrowForward as ArrowIcon
} from '@mui/icons-material';
import { PieChart, Pie, Cell, ResponsiveContainer, Legend, Tooltip } from 'recharts';
import { useApi } from '../hooks';

const Dashboard = () => {
  const { get, loading, error } = useApi();
  const [stats, setStats] = useState({
    totalStudents: 0,
    totalTeachers: 0,
    totalCourses: 0,
    totalClasses: 0,
    totalDepartments: 0,
    totalGrades: 0,
    totalAttendances: 0
  });

  useEffect(() => {
    fetchStats();
  }, []); // eslint-disable-line react-hooks/exhaustive-deps

  const fetchStats = async () => {
    try {
      const data = await get('/statistics');
      setStats(data);
    } catch (err) {
      console.error('Error fetching statistics:', err);
    }
  };

  if (loading) {
    return (
      <Box display="flex" justifyContent="center" alignItems="center" minHeight="400px">
        <CircularProgress />
      </Box>
    );
  }

  if (error) {
    return <Alert severity="error">Error: {error}</Alert>;
  }

  // Biểu đồ thống kê lượng sinh viên nghỉ học
  const attendanceData = [
    { name: 'Sinh viên nghỉ > 5', value: 15, color: '#2e7d32' },
    { name: 'Sinh viên nghỉ = 4', value: 8, color: '#ffa726' },
    { name: 'Sinh viên nghỉ = 3', value: 12, color: '#7e57c2' },
    { name: 'Sinh viên nghỉ = 2', value: 20, color: '#26a69a' },
    { name: 'Sinh viên nghỉ <= 1', value: 45, color: '#42a5f5' }
  ];

  // Biểu đồ đánh điểm học tập
  const gradeData = [
    { name: 'Điểm < 5', value: 25, color: '#ef5350' },
    { name: 'Điểm >= 5 và < 8', value: 40, color: '#42a5f5' },
    { name: 'Điểm >= 8', value: 35, color: '#66bb6a' }
  ];

  const statCards = [
    {
      title: 'Khoa',
      value: stats.totalDepartments,
      icon: DepartmentIcon,
      bgColor: '#0dcaf0',
      link: '/departments'
    },
    {
      title: 'Lớp',
      value: stats.totalClasses,
      icon: ClassIcon,
      bgColor: '#198754',
      link: '/classes'
    },
    {
      title: 'Sinh viên',
      value: stats.totalStudents,
      icon: StudentIcon,
      bgColor: '#ffc107',
      link: '/students'
    },
    {
      title: 'Môn học',
      value: stats.totalCourses,
      icon: CourseIcon,
      bgColor: '#dc3545',
      link: '/courses'
    },
    {
      title: 'Giảng viên',
      value: stats.totalTeachers,
      icon: TeacherIcon,
      bgColor: '#ffc107',
      link: '/teachers'
    },
  ];

  return (
    <Container maxWidth="xl" sx={{ py: 4, px: { xs: 2, sm: 3, md: 4 } }}>
      {/* Statistics Cards */}
      <Grid container spacing={3} sx={{ mb: 4, justifyContent: 'center', alignItems: 'stretch' }}>
        {statCards.map((stat, index) => {
          const IconComponent = stat.icon;
          return (
            <Grid item xs={12} sm={6} md={4} lg={2.4} key={index}>
              <Card
                sx={{
                  bgcolor: stat.bgColor,
                  color: 'white',
                  height: '100%',
                  transition: 'all 0.3s',
                  cursor: 'pointer',
                  '&:hover': {
                    transform: 'translateY(-4px)',
                    boxShadow: '0 8px 16px rgba(0,0,0,0.2)',
                  },
                }}
              >
                <CardContent>
                  <Box display="flex" flexDirection="column" alignItems="flex-start">
                    <Typography 
                      variant="h3" 
                      sx={{ 
                        fontWeight: 700, 
                        mb: 1,
                        fontSize: { xs: '2rem', md: '2.5rem' }
                      }}
                    >
                      {stat.value}
                    </Typography>
                    <Typography variant="h6" sx={{ mb: 2, fontWeight: 500 }}>
                      {stat.title}
                    </Typography>
                    <Button
                      size="small"
                      endIcon={<ArrowIcon />}
                      sx={{
                        color: 'white',
                        bgcolor: 'rgba(255,255,255,0.2)',
                        '&:hover': {
                          bgcolor: 'rgba(255,255,255,0.3)',
                        },
                      }}
                      onClick={() => window.location.href = stat.link}
                    >
                      Xem thêm
                    </Button>
                  </Box>
                  <Box
                    sx={{
                      position: 'absolute',
                      top: 16,
                      right: 16,
                      opacity: 0.3,
                    }}
                  >
                    <IconComponent sx={{ fontSize: 48 }} />
                  </Box>
                </CardContent>
              </Card>
            </Grid>
          );
        })}
      </Grid>

      {/* Charts Section */}
      <Grid container spacing={3} sx={{ justifyContent: 'center', maxWidth: '1400px', margin: '0 auto' }}>
        {/* Biểu đồ thống kê lượng sinh viên nghỉ học */}
        <Grid item xs={12} md={6}>
          <Card
            sx={{
              height: '100%',
              borderRadius: 2,
              boxShadow: '0 4px 12px rgba(0,0,0,0.1)',
            }}
          >
            <CardContent>
              <Box display="flex" alignItems="center" gap={1} mb={3}>
                <Box
                  sx={{
                    width: 8,
                    height: 8,
                    borderRadius: '50%',
                    bgcolor: '#dc3545',
                  }}
                />
                <Typography variant="h6" sx={{ fontWeight: 600, color: '#dc3545' }}>
                  Biểu đồ thống kê lượng sinh viên nghỉ học
                </Typography>
              </Box>
              <ResponsiveContainer width="100%" height={350}>
                <PieChart>
                  <Pie
                    data={attendanceData}
                    cx="50%"
                    cy="50%"
                    labelLine={false}
                    outerRadius={120}
                    fill="#8884d8"
                    dataKey="value"
                  >
                    {attendanceData.map((entry, index) => (
                      <Cell key={`cell-${index}`} fill={entry.color} />
                    ))}
                  </Pie>
                  <Tooltip />
                  <Legend 
                    verticalAlign="bottom" 
                    height={36}
                    iconType="circle"
                  />
                </PieChart>
              </ResponsiveContainer>
            </CardContent>
          </Card>
        </Grid>

        {/* Biểu đồ đánh điểm học tập */}
        <Grid item xs={12} md={6}>
          <Card
            sx={{
              height: '100%',
              borderRadius: 2,
              boxShadow: '0 4px 12px rgba(0,0,0,0.1)',
            }}
          >
            <CardContent>
              <Box display="flex" alignItems="center" gap={1} mb={3}>
                <Box
                  sx={{
                    width: 8,
                    height: 8,
                    borderRadius: '50%',
                    bgcolor: '#198754',
                  }}
                />
                <Typography variant="h6" sx={{ fontWeight: 600, color: '#198754' }}>
                  Biểu đồ đánh điểm học tập
                </Typography>
              </Box>
              <ResponsiveContainer width="100%" height={350}>
                <PieChart>
                  <Pie
                    data={gradeData}
                    cx="50%"
                    cy="50%"
                    labelLine={false}
                    outerRadius={120}
                    fill="#8884d8"
                    dataKey="value"
                  >
                    {gradeData.map((entry, index) => (
                      <Cell key={`cell-${index}`} fill={entry.color} />
                    ))}
                  </Pie>
                  <Tooltip />
                  <Legend 
                    verticalAlign="bottom" 
                    height={36}
                    iconType="circle"
                  />
                </PieChart>
              </ResponsiveContainer>
            </CardContent>
          </Card>
        </Grid>
      </Grid>
    </Container>
  );
};

export default Dashboard;



