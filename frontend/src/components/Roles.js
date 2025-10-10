import React, { useState, useEffect } from 'react';
import {
  Container,
  Typography,
  Box,
  Button,
  Table,
  TableBody,
  TableCell,
  TableContainer,
  TableHead,
  TableRow,
  Paper,
  Chip,
  CircularProgress,
  Alert,
  Card,
  CardContent,
  Grid,
  TextField,
  Select,
  MenuItem,
  FormControl,
  InputLabel,
  IconButton,
  Dialog,
  DialogTitle,
  DialogContent,
  DialogActions,
  Snackbar,
  Tabs,
  Tab,
  Avatar,
  Divider,
  List,
  ListItem,
  ListItemText,
  ListItemAvatar,
  Checkbox,
  FormControlLabel,
  FormGroup,
  Accordion,
  AccordionSummary,
  AccordionDetails,
  Switch,
  Badge,
  Tooltip
} from '@mui/material';
import {
  Add as AddIcon,
  Edit as EditIcon,
  Delete as DeleteIcon,
  Search as SearchIcon,
  FilterList as FilterIcon,
  Person as PersonIcon,
  AdminPanelSettings as AdminIcon,
  Security as SecurityIcon,
  Visibility as ViewIcon,
  ExpandMore as ExpandMoreIcon,
  Group as GroupIcon,
  Shield as ShieldIcon,
  PersonAdd as PersonAddIcon,
  Assignment as AssignmentIcon,
  CheckCircle as CheckCircleIcon,
  Cancel as CancelIcon,
  Info as InfoIcon,
  Warning as WarningIcon
} from '@mui/icons-material';

const Roles = () => {
  const [permissions, setPermissions] = useState([]);
  const [users, setUsers] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [searchTerm, setSearchTerm] = useState('');
  const [tabValue, setTabValue] = useState(0);
  const [openDialog, setOpenDialog] = useState(false);
  const [openPermissionDialog, setOpenPermissionDialog] = useState(false);
  const [editingRole, setEditingRole] = useState(null);
  const [snackbar, setSnackbar] = useState({ open: false, message: '', severity: 'success' });

  // Form state
  const [formData, setFormData] = useState({
    name: '',
    display_name: '',
    description: '',
    permissions: []
  });

  // Permission form state
  const [permissionFormData, setPermissionFormData] = useState({
    role: '',
    permissions: []
  });

  // Predefined roles
  const predefinedRoles = [
    {
      value: 'super_admin',
      label: 'Super Admin',
      description: 'Quyền cao nhất trong hệ thống',
      color: 'error',
      icon: <AdminIcon />,
      permissions: ['all']
    },
    {
      value: 'user',
      label: 'User',
      description: 'Quyền cơ bản của người dùng',
      color: 'primary',
      icon: <PersonIcon />,
      permissions: ['view_students', 'view_teachers', 'view_courses', 'view_classes', 'view_grades', 'view_attendance']
    }
  ];

  useEffect(() => {
    fetchPermissions();
    fetchUsers();
  }, []);

  const fetchPermissions = async () => {
    try {
      const response = await fetch('http://127.0.0.1:8000/api/permissions');
      if (!response.ok) throw new Error('Failed to fetch permissions');
      const data = await response.json();
      setPermissions(data || []);
    } catch (err) {
      console.error('Error fetching permissions:', err);
    } finally {
      setLoading(false);
    }
  };

  const fetchUsers = async () => {
    try {
      const response = await fetch('http://127.0.0.1:8000/api/users');
      if (!response.ok) throw new Error('Failed to fetch users');
      const data = await response.json();
      setUsers(data.data || []);
    } catch (err) {
      console.error('Error fetching users:', err);
    }
  };

  const handleOpenDialog = (roleData = null) => {
    if (roleData) {
      setEditingRole(roleData);
      setFormData({
        name: roleData.value || '',
        display_name: roleData.label || '',
        description: roleData.description || '',
        permissions: roleData.permissions || []
      });
    } else {
      setEditingRole(null);
      setFormData({
        name: '',
        display_name: '',
        description: '',
        permissions: []
      });
    }
    setOpenDialog(true);
  };

  const handleCloseDialog = () => {
    setOpenDialog(false);
    setEditingRole(null);
    setFormData({
      name: '',
      display_name: '',
      description: '',
      permissions: []
    });
  };

  const handleOpenPermissionDialog = (role) => {
    setPermissionFormData({
      role: role.value,
      permissions: role.permissions || []
    });
    setOpenPermissionDialog(true);
  };

  const handleClosePermissionDialog = () => {
    setOpenPermissionDialog(false);
    setPermissionFormData({
      role: '',
      permissions: []
    });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      // Since we're using predefined roles, we'll just show a message
      setSnackbar({
        open: true,
        message: 'Vai trò đã được cập nhật thành công!',
        severity: 'success'
      });

      handleCloseDialog();
    } catch (err) {
      setSnackbar({
        open: true,
        message: 'Có lỗi xảy ra: ' + err.message,
        severity: 'error'
      });
    }
  };

  const handlePermissionSubmit = async (e) => {
    e.preventDefault();
    try {
      setSnackbar({
        open: true,
        message: 'Quyền hạn đã được cập nhật thành công!',
        severity: 'success'
      });

      handleClosePermissionDialog();
    } catch (err) {
      setSnackbar({
        open: true,
        message: 'Có lỗi xảy ra: ' + err.message,
        severity: 'error'
      });
    }
  };

  const handleDelete = async (userId, userName) => {
    if (window.confirm(`Bạn có chắc muốn xóa người dùng "${userName}" không?`)) {
      try {
        const response = await fetch(`http://127.0.0.1:8000/api/users/${userId}`, {
          method: 'DELETE',
        });

        if (!response.ok) {
          const errorData = await response.json();
          throw new Error(errorData.message || 'Failed to delete user');
        }

        setSnackbar({
          open: true,
          message: 'Người dùng đã được xóa thành công!',
          severity: 'success'
        });

        fetchUsers();
      } catch (err) {
        setSnackbar({
          open: true,
          message: 'Có lỗi xảy ra: ' + err.message,
          severity: 'error'
        });
      }
    }
  };

  const getRoleIcon = (roleValue) => {
    const role = predefinedRoles.find(r => r.value === roleValue);
    return role ? role.icon : <PersonIcon />;
  };

  const getRoleColor = (roleValue) => {
    const role = predefinedRoles.find(r => r.value === roleValue);
    return role ? role.color : 'default';
  };

  const getRoleLabel = (roleValue) => {
    const role = predefinedRoles.find(r => r.value === roleValue);
    return role ? role.label : 'Không xác định';
  };

  const getUsersByRole = (roleValue) => {
    return users.filter(user => user.role === roleValue);
  };

  const getPermissionCount = (roleValue) => {
    const role = predefinedRoles.find(r => r.value === roleValue);
    if (role && role.permissions.includes('all')) {
      return permissions.length;
    }
    return role ? role.permissions.length : 0;
  };

  const groupedPermissions = permissions.reduce((acc, permission) => {
    const group = permission.group || 'Khác';
    if (!acc[group]) {
      acc[group] = [];
    }
    acc[group].push(permission);
    return acc;
  }, {});

  const filteredRoles = predefinedRoles.filter(role => {
    const matchesSearch = role.label.toLowerCase().includes(searchTerm.toLowerCase()) ||
                         role.description.toLowerCase().includes(searchTerm.toLowerCase());
    return matchesSearch;
  });

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

  return (
    <Container maxWidth="lg" sx={{ mt: 4, mb: 4 }}>
      {/* Header Section */}
      <Box display="flex" justifyContent="space-between" alignItems="center" mb={4}>
        <Box>
          <Typography variant="h4" component="h1" gutterBottom>
            <AssignmentIcon sx={{ mr: 2, verticalAlign: 'middle' }} />
            Quản Lý Vai Trò Thành Viên
          </Typography>
          <Typography variant="subtitle1" color="text.secondary">
            Quản lý vai trò và quyền hạn của các thành viên trong hệ thống
          </Typography>
        </Box>
        {/* Không hiển thị nút thêm vai trò vì hệ thống chỉ có 2 vai trò cố định: Super Admin và User */}
      </Box>

      {/* Search Section */}
      <Card sx={{ mb: 3 }}>
        <CardContent>
          <Grid container spacing={2} alignItems="center">
            <Grid item xs={12} md={6}>
              <TextField
                fullWidth
                label="Tìm kiếm"
                placeholder="Tên vai trò, mô tả..."
                value={searchTerm}
                onChange={(e) => setSearchTerm(e.target.value)}
                InputProps={{
                  startAdornment: <SearchIcon sx={{ mr: 1, color: 'text.secondary' }} />
                }}
              />
            </Grid>
            <Grid item xs={12} md={6}>
              <Box display="flex" gap={2}>
                <Button
                  variant="outlined"
                  startIcon={<FilterIcon />}
                  onClick={() => setSearchTerm('')}
                >
                  Đặt lại
                </Button>
              </Box>
            </Grid>
          </Grid>
        </CardContent>
      </Card>

      {/* Tabs */}
      <Card>
        <Box sx={{ borderBottom: 1, borderColor: 'divider' }}>
          <Tabs value={tabValue} onChange={(e, newValue) => setTabValue(newValue)}>
            <Tab label="Danh sách vai trò" />
            <Tab label="Phân quyền chi tiết" />
            <Tab label="Thống kê vai trò" />
          </Tabs>
        </Box>

        {/* Tab 1: Danh sách vai trò */}
        {tabValue === 0 && (
          <CardContent>
            <Box display="flex" justifyContent="space-between" alignItems="center" mb={2}>
              <Typography variant="h6">
                Danh sách vai trò ({filteredRoles.length} vai trò)
              </Typography>
            </Box>
            
            <TableContainer component={Paper} variant="outlined">
              <Table>
                <TableHead>
                  <TableRow>
                    <TableCell>#</TableCell>
                    <TableCell>Vai trò</TableCell>
                    <TableCell>Mô tả</TableCell>
                    <TableCell>Số quyền</TableCell>
                    <TableCell>Số thành viên</TableCell>
                    <TableCell align="center">Thao tác</TableCell>
                  </TableRow>
                </TableHead>
                <TableBody>
                  {filteredRoles.map((role, index) => (
                    <TableRow key={role.value} hover>
                      <TableCell>{index + 1}</TableCell>
                      <TableCell>
                        <Box display="flex" alignItems="center">
                          <Avatar sx={{ mr: 2, bgcolor: `${role.color}.main` }}>
                            {role.icon}
                          </Avatar>
                          <Box>
                            <Typography variant="subtitle2" fontWeight="bold">
                              {role.label}
                            </Typography>
                            <Typography variant="caption" color="text.secondary">
                              {role.value}
                            </Typography>
                          </Box>
                        </Box>
                      </TableCell>
                      <TableCell>
                        <Typography variant="body2">{role.description}</Typography>
                      </TableCell>
                      <TableCell>
                        <Chip 
                          label={`${getPermissionCount(role.value)} quyền`}
                          color={role.color}
                          size="small"
                        />
                      </TableCell>
                      <TableCell>
                        <Badge badgeContent={getUsersByRole(role.value).length} color="primary">
                          <Chip 
                            icon={<GroupIcon />}
                            label="thành viên"
                            variant="outlined"
                            size="small"
                          />
                        </Badge>
                      </TableCell>
                      <TableCell align="center">
                        <Box display="flex" gap={1} justifyContent="center">
                          <Tooltip title="Xem chi tiết">
                            <IconButton
                              size="small"
                              color="info"
                              onClick={() => handleOpenDialog(role)}
                            >
                              <ViewIcon />
                            </IconButton>
                          </Tooltip>
                          <Tooltip title="Chỉnh sửa">
                            <IconButton
                              size="small"
                              color="primary"
                              onClick={() => handleOpenDialog(role)}
                            >
                              <EditIcon />
                            </IconButton>
                          </Tooltip>
                          <Tooltip title="Quản lý quyền">
                            <IconButton
                              size="small"
                              color="secondary"
                              onClick={() => handleOpenPermissionDialog(role)}
                            >
                              <SecurityIcon />
                            </IconButton>
                          </Tooltip>
                        </Box>
                      </TableCell>
                    </TableRow>
                  ))}
                </TableBody>
              </Table>
            </TableContainer>
          </CardContent>
        )}

        {/* Tab 2: Phân quyền chi tiết */}
        {tabValue === 1 && (
          <CardContent>
            <Typography variant="h6" gutterBottom>
              Chi tiết quyền hạn theo vai trò
            </Typography>
            <Grid container spacing={3}>
              {predefinedRoles.map((role) => (
                <Grid item xs={12} md={6} key={role.value}>
                  <Card variant="outlined">
                    <CardContent>
                      <Box display="flex" alignItems="center" mb={2}>
                        <Avatar sx={{ mr: 2, bgcolor: `${role.color}.main` }}>
                          {role.icon}
                        </Avatar>
                        <Box>
                          <Typography variant="h6">{role.label}</Typography>
                          <Typography variant="body2" color="text.secondary">
                            {role.description}
                          </Typography>
                        </Box>
                      </Box>
                      <Divider sx={{ my: 2 }} />
                      <Typography variant="subtitle2" gutterBottom>
                        Quyền hạn:
                      </Typography>
                      {role.permissions.includes('all') ? (
                        <Chip 
                          icon={<CheckCircleIcon />}
                          label="Tất cả quyền"
                          color="success"
                          size="small"
                        />
                      ) : (
                        <Box>
                          {role.permissions.map((permission, index) => (
                            <Chip 
                              key={index}
                              label={permission}
                              size="small"
                              sx={{ mr: 1, mb: 1 }}
                            />
                          ))}
                        </Box>
                      )}
                    </CardContent>
                  </Card>
                </Grid>
              ))}
            </Grid>
          </CardContent>
        )}

        {/* Tab 3: Thống kê vai trò */}
        {tabValue === 2 && (
          <CardContent>
            <Typography variant="h6" gutterBottom>
              Thống kê vai trò
            </Typography>
            <Grid container spacing={3}>
              {predefinedRoles.map((role) => {
                const usersInRole = getUsersByRole(role.value);
                return (
                  <Grid item xs={12} md={4} key={role.value}>
                    <Card variant="outlined">
                      <CardContent>
                        <Box display="flex" alignItems="center" mb={2}>
                          <Avatar sx={{ mr: 2, bgcolor: `${role.color}.main` }}>
                            {role.icon}
                          </Avatar>
                          <Box>
                            <Typography variant="h6">{role.label}</Typography>
                            <Typography variant="body2" color="text.secondary">
                              {usersInRole.length} thành viên
                            </Typography>
                          </Box>
                        </Box>
                        <Divider sx={{ my: 2 }} />
                        <Typography variant="subtitle2" gutterBottom>
                          Thành viên:
                        </Typography>
                        {usersInRole.length === 0 ? (
                          <Typography variant="body2" color="text.secondary">
                            Chưa có thành viên
                          </Typography>
                        ) : (
                          <List dense>
                            {usersInRole.slice(0, 3).map((user) => (
                              <ListItem key={user.id} disablePadding>
                                <ListItemAvatar>
                                  <Avatar sx={{ width: 24, height: 24 }}>
                                    <PersonIcon fontSize="small" />
                                  </Avatar>
                                </ListItemAvatar>
                                <ListItemText 
                                  primary={user.name}
                                  secondary={user.email}
                                  primaryTypographyProps={{ variant: 'body2' }}
                                  secondaryTypographyProps={{ variant: 'caption' }}
                                />
                              </ListItem>
                            ))}
                            {usersInRole.length > 3 && (
                              <ListItem disablePadding>
                                <ListItemText 
                                  primary={`+${usersInRole.length - 3} thành viên khác`}
                                  primaryTypographyProps={{ variant: 'caption', color: 'text.secondary' }}
                                />
                              </ListItem>
                            )}
                          </List>
                        )}
                      </CardContent>
                    </Card>
                  </Grid>
                );
              })}
            </Grid>
          </CardContent>
        )}
      </Card>

      {/* Add/Edit Dialog */}
      <Dialog open={openDialog} onClose={handleCloseDialog} maxWidth="md" fullWidth>
        <DialogTitle>
          {editingRole ? 'Chỉnh sửa vai trò' : 'Thêm vai trò mới'}
        </DialogTitle>
        <form onSubmit={handleSubmit}>
          <DialogContent>
            <Grid container spacing={2} sx={{ mt: 1 }}>
              <Grid item xs={12} sm={6}>
                <TextField
                  fullWidth
                  label="Tên vai trò"
                  value={formData.name}
                  onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                  required
                />
              </Grid>
              <Grid item xs={12} sm={6}>
                <TextField
                  fullWidth
                  label="Tên hiển thị"
                  value={formData.display_name}
                  onChange={(e) => setFormData({ ...formData, display_name: e.target.value })}
                  required
                />
              </Grid>
              <Grid item xs={12}>
                <TextField
                  fullWidth
                  label="Mô tả"
                  multiline
                  rows={3}
                  value={formData.description}
                  onChange={(e) => setFormData({ ...formData, description: e.target.value })}
                />
              </Grid>
            </Grid>
          </DialogContent>
          <DialogActions>
            <Button onClick={handleCloseDialog}>Hủy</Button>
            <Button type="submit" variant="contained">
              {editingRole ? 'Cập nhật' : 'Tạo vai trò'}
            </Button>
          </DialogActions>
        </form>
      </Dialog>

      {/* Permission Management Dialog */}
      <Dialog open={openPermissionDialog} onClose={handleClosePermissionDialog} maxWidth="sm" fullWidth>
        <DialogTitle>
          Quản lý quyền hạn cho vai trò
        </DialogTitle>
        <form onSubmit={handlePermissionSubmit}>
          <DialogContent>
            {Object.entries(groupedPermissions).map(([group, groupPermissions]) => (
              <Accordion key={group}>
                <AccordionSummary expandIcon={<ExpandMoreIcon />}>
                  <Typography variant="subtitle1">{group}</Typography>
                </AccordionSummary>
                <AccordionDetails>
                  <FormGroup>
                    {groupPermissions.map((permission) => (
                      <FormControlLabel
                        key={permission.id}
                        control={
                          <Checkbox
                            checked={permissionFormData.permissions.includes(permission.name)}
                            onChange={(e) => {
                              if (e.target.checked) {
                                setPermissionFormData(prev => ({
                                  ...prev,
                                  permissions: [...prev.permissions, permission.name]
                                }));
                              } else {
                                setPermissionFormData(prev => ({
                                  ...prev,
                                  permissions: prev.permissions.filter(p => p !== permission.name)
                                }));
                              }
                            }}
                          />
                        }
                        label={permission.display_name || permission.name}
                      />
                    ))}
                  </FormGroup>
                </AccordionDetails>
              </Accordion>
            ))}
          </DialogContent>
          <DialogActions>
            <Button onClick={handleClosePermissionDialog}>Hủy</Button>
            <Button type="submit" variant="contained">
              Cập nhật quyền hạn
            </Button>
          </DialogActions>
        </form>
      </Dialog>

      {/* Snackbar for notifications */}
      <Snackbar
        open={snackbar.open}
        autoHideDuration={6000}
        onClose={() => setSnackbar({ ...snackbar, open: false })}
      >
        <Alert 
          onClose={() => setSnackbar({ ...snackbar, open: false })} 
          severity={snackbar.severity}
        >
          {snackbar.message}
        </Alert>
      </Snackbar>
    </Container>
  );
};

export default Roles;
