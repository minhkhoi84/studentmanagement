import React, { useState, useEffect } from 'react';
import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';
import { ThemeProvider, createTheme } from '@mui/material/styles';
import CssBaseline from '@mui/material/CssBaseline';
import { LocalizationProvider } from '@mui/x-date-pickers/LocalizationProvider';
import { AdapterDateFns } from '@mui/x-date-pickers/AdapterDateFns';
import { 
  Container, 
  AppBar, 
  Toolbar, 
  Typography, 
  Box, 
  Drawer, 
  List, 
  ListItem, 
  ListItemButton, 
  ListItemIcon, 
  ListItemText,
  Button,
  Alert,
  CircularProgress
} from '@mui/material';
import {
  Dashboard as DashboardIcon,
  People as PeopleIcon,
  School as SchoolIcon,
  Book as BookIcon,
  Assessment as AssessmentIcon,
  Login as LoginIcon,
  Logout as LogoutIcon,
  Business as BusinessIcon,
  Class as ClassIcon,
  Person as PersonIcon,
  Group as GroupIcon
} from '@mui/icons-material';
import {
  Dashboard,
  Students,
  Teachers,
  Courses,
  Grades,
  Departments,
  Classes,
  Attendance,
  Users,
  Roles,
} from './components';

const theme = createTheme({
  palette: {
    primary: {
      main: '#1976d2',
    },
    secondary: {
      main: '#dc004e',
    },
  },
});

const drawerWidth = 240;


const AppContent = () => {
  const [currentPage, setCurrentPage] = useState('dashboard');
  const [mobileOpen, setMobileOpen] = useState(false);

  const handleDrawerToggle = () => {
    setMobileOpen(!mobileOpen);
  };

  const menuItems = [
    { text: 'Trang chủ', icon: <DashboardIcon />, page: 'dashboard' },
    { text: 'Khoa', icon: <BusinessIcon />, page: 'departments' },
    { text: 'Lớp', icon: <ClassIcon />, page: 'classes' },
    { text: 'Sinh viên', icon: <PeopleIcon />, page: 'students' },
    { text: 'Quản lý giảng viên', icon: <SchoolIcon />, page: 'teachers' },
    { text: 'Quản lý môn học', icon: <BookIcon />, page: 'courses' },
    { text: 'Quản lý điểm', icon: <AssessmentIcon />, page: 'grades' },
    { text: 'Quản lý điểm danh', icon: <AssessmentIcon />, page: 'attendance' },
    { text: 'Thành viên', icon: <GroupIcon />, page: 'users' },
    { text: 'Vai trò thành viên', icon: <PersonIcon />, page: 'roles' },
  ];

  const drawer = (
    <div>
      <Toolbar>
        <Typography variant="h6" noWrap component="div">
          Quản lý sinh viên
        </Typography>
      </Toolbar>
      <List>
        {menuItems.map((item) => (
          <ListItem key={item.page} disablePadding>
            <ListItemButton
              selected={currentPage === item.page}
              onClick={() => {
                setCurrentPage(item.page);
                setMobileOpen(false);
              }}
            >
              <ListItemIcon>
                {item.icon}
              </ListItemIcon>
              <ListItemText primary={item.text} />
            </ListItemButton>
          </ListItem>
        ))}
      </List>
    </div>
  );

  const renderContent = () => {
    switch (currentPage) {
      case 'dashboard':
        return <Dashboard />;
      case 'students':
        return <Students />;
      case 'departments':
        return <Departments />;
      case 'classes':
        return <Classes />;
      case 'teachers':
        return <Teachers />;
      case 'courses':
        return <Courses />;
      case 'grades':
        return <Grades />;
      case 'attendance':
        return <Attendance />;
      case 'users':
        return <Users />;
      case 'roles':
        return <Roles />;
      default:
        return <Dashboard />;
    }
  };

  return (
    <Box sx={{ display: 'flex' }}>
      <AppBar
        position="fixed"
        sx={{
          width: { sm: `calc(100% - ${drawerWidth}px)` },
          ml: { sm: `${drawerWidth}px` },
        }}
      >
        <Toolbar>
          <Typography variant="h6" noWrap component="div">
            Hệ thống quản lý sinh viên
          </Typography>
        </Toolbar>
      </AppBar>
      
      <Box
        component="nav"
        sx={{ width: { sm: drawerWidth }, flexShrink: { sm: 0 } }}
      >
        <Drawer
          variant="temporary"
          open={mobileOpen}
          onClose={handleDrawerToggle}
          ModalProps={{
            keepMounted: true,
          }}
          sx={{
            display: { xs: 'block', sm: 'none' },
            '& .MuiDrawer-paper': { boxSizing: 'border-box', width: drawerWidth },
          }}
        >
          {drawer}
        </Drawer>
        <Drawer
          variant="permanent"
          sx={{
            display: { xs: 'none', sm: 'block' },
            '& .MuiDrawer-paper': { boxSizing: 'border-box', width: drawerWidth },
          }}
          open
        >
          {drawer}
        </Drawer>
      </Box>
      
      <Box
        component="main"
        sx={{ 
          flexGrow: 1, 
          p: 3, 
          width: { sm: `calc(100% - ${drawerWidth}px)` } 
        }}
      >
        <Toolbar />
        {renderContent()}
      </Box>
    </Box>
  );
};

function App() {
  return (
    <LocalizationProvider dateAdapter={AdapterDateFns}>
      <ThemeProvider theme={theme}>
        <CssBaseline />
        <Router>
          <AppContent />
        </Router>
      </ThemeProvider>
    </LocalizationProvider>
  );
}

export default App;