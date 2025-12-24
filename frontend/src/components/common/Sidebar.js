import React from 'react';
import {
  Drawer,
  List,
  ListItem,
  ListItemButton,
  ListItemIcon,
  ListItemText,
  Box,
  Typography,
  IconButton
} from '@mui/material';
import {
  Dashboard as DashboardIcon,
  People as PeopleIcon,
  School as SchoolIcon,
  Book as BookIcon,
  Assessment as AssessmentIcon,
  Business as BusinessIcon,
  Class as ClassIcon,
  CheckCircle as CheckCircleIcon,
  Menu as MenuIcon
} from '@mui/icons-material';
import { useLocation } from 'react-router-dom';

const Sidebar = ({ mobileOpen, handleDrawerToggle, drawerWidth }) => {
  const location = useLocation();
  const currentPage = location.pathname.substring(1) || 'dashboard';

  const menuItems = [
    { text: 'Trang chủ', icon: <DashboardIcon />, page: 'dashboard' },
    { text: 'Khoa', icon: <BusinessIcon />, page: 'departments' },
    { text: 'Lớp', icon: <ClassIcon />, page: 'classes' },
    { text: 'Sinh viên', icon: <PeopleIcon />, page: 'students' },
    { text: 'Quản lý giảng viên', icon: <SchoolIcon />, page: 'teachers' },
    { text: 'Quản môn học', icon: <BookIcon />, page: 'courses' },
    { text: 'Quản lý điểm', icon: <AssessmentIcon />, page: 'grades' },
    { text: 'Quản lý điểm danh', icon: <CheckCircleIcon />, page: 'attendance' },
  ];

  const drawer = (
    <Box sx={{ height: '100%', bgcolor: '#3b5998' }}>
      {/* Header */}
      <Box
        sx={{
          p: 2,
          display: 'flex',
          alignItems: 'center',
          justifyContent: 'space-between',
          bgcolor: '#2d4373',
          color: 'white',
        }}
      >
        <Typography variant="h6" sx={{ fontWeight: 600 }}>
          Quản lý SV
        </Typography>
        <IconButton
          size="small"
          sx={{ color: 'white', display: { sm: 'none' } }}
          onClick={handleDrawerToggle}
        >
          <MenuIcon />
        </IconButton>
      </Box>

      {/* Menu Items */}
      <List sx={{ pt: 1 }}>
        {menuItems.map((item) => {
          const isActive = currentPage === item.page;
          return (
            <ListItem key={item.page} disablePadding>
              <ListItemButton
                selected={isActive}
                component="a"
                href={`/${item.page}`}
                onClick={(e) => {
                  e.preventDefault();
                  window.location.href = `/${item.page}`;
                  if (handleDrawerToggle) handleDrawerToggle();
                }}
                sx={{
                  py: 1.5,
                  px: 2,
                  color: 'rgba(255,255,255,0.8)',
                  '&.Mui-selected': {
                    bgcolor: 'rgba(255,255,255,0.1)',
                    color: 'white',
                    borderLeft: '4px solid #fff',
                    '&:hover': {
                      bgcolor: 'rgba(255,255,255,0.15)',
                    },
                  },
                  '&:hover': {
                    bgcolor: 'rgba(255,255,255,0.08)',
                  },
                }}
              >
                <ListItemIcon sx={{ color: 'inherit', minWidth: 40 }}>
                  {item.icon}
                </ListItemIcon>
                <ListItemText 
                  primary={item.text}
                  primaryTypographyProps={{
                    fontSize: '0.9rem',
                    fontWeight: isActive ? 500 : 400,
                  }}
                />
              </ListItemButton>
            </ListItem>
          );
        })}
      </List>
    </Box>
  );

  return (
    <Box
      component="nav"
      sx={{ width: { sm: drawerWidth }, flexShrink: { sm: 0 } }}
    >
      {/* Mobile drawer */}
      <Drawer
        variant="temporary"
        open={mobileOpen}
        onClose={handleDrawerToggle}
        ModalProps={{
          keepMounted: true,
        }}
        sx={{
          display: { xs: 'block', sm: 'none' },
          '& .MuiDrawer-paper': {
            boxSizing: 'border-box',
            width: drawerWidth,
          },
        }}
      >
        {drawer}
      </Drawer>
      
      {/* Desktop drawer */}
      <Drawer
        variant="permanent"
        sx={{
          display: { xs: 'none', sm: 'block' },
          '& .MuiDrawer-paper': {
            boxSizing: 'border-box',
            width: drawerWidth,
          },
        }}
        open
      >
        {drawer}
      </Drawer>
    </Box>
  );
};

export default Sidebar;
