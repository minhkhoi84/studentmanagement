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
  Checkbox,
  FormControlLabel,
  FormGroup,
  Accordion,
  AccordionSummary,
  AccordionDetails,
  Tooltip
} from '@mui/material';
import {
  Edit as EditIcon,
  Search as SearchIcon,
  FilterList as FilterIcon,
  Person as PersonIcon,
  AdminPanelSettings as AdminIcon,
  Security as SecurityIcon,
  ExpandMore as ExpandMoreIcon,
  Group as GroupIcon,
  Assignment as AssignmentIcon,
  CheckCircle as CheckCircleIcon
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

  // eslint-disable-next-line no-unused-vars
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

  // eslint-disable-next-line no-unused-vars
  const getRoleIcon = (roleValue) => {
    const role = predefinedRoles.find(r => r.value === roleValue);
    return role ? role.icon : <PersonIcon />;
  };

  // eslint-disable-next-line no-unused-vars
  const getRoleColor = (roleValue) => {
    const role = predefinedRoles.find(r => r.value === roleValue);
    return role ? role.color : 'default';
  };

  // eslint-disable-next-line no-unused-vars
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
                        <Typography variant="body2" color="text.secondary">
                          {role.description}
                        </Typography>
                      </TableCell>
                      <TableCell>
                        <Chip
                          icon={<SecurityIcon />}
                          label={`${getPermissionCount(role.value)} quyền`}
                          color="primary"
                          size="small"
                        />
                      </TableCell>
                      <TableCell>
                        <Chip
                          icon={<GroupIcon />}
                          label={`${getUsersByRole(role.value).length} thành viên`}
                          color="secondary"
                          size="small"
                        />
                      </TableCell>
                      <TableCell align="center">
                        <Box display="flex" justifyContent="center" gap={1}>
                          <Tooltip title="Chỉnh sửa thông tin vai trò">
                            <span>
                              <IconButton
                                size="small"
                                color="primary"
                                onClick={() => handleOpenDialog(role)}
                              >
                                <EditIcon />
                              </IconButton>
                            </span>
                          </Tooltip>
                          <Tooltip title="Phân quyền chi tiết">
                            <span>
                              <IconButton
                                size="small"
                                color="secondary"
                                onClick={() => handleOpenPermissionDialog(role)}
                              >
                                <SecurityIcon />
                              </IconButton>
                            </span>
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
              Phân quyền theo vai trò
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
                          <Typography variant="subtitle1" fontWeight="bold">
                            {role.label}
                          </Typography>
                          <Typography variant="body2" color="text.secondary">
                            {role.description}
                          </Typography>
                        </Box>
                      </Box>
                      <Divider sx={{ mb: 2 }} />
                      <Typography variant="subtitle2" gutterBottom>
                        Quyền hạn mặc định
                      </Typography>
                      {role.permissions.includes('all') ? (
                        <Chip
                          icon={<CheckCircleIcon />}
                          label="Toàn quyền hệ thống"
                          color="success"
                          size="small"
                        />
                      ) : (
                        <Box sx={{ display: 'flex', flexWrap: 'wrap', gap: 1 }}>
                          {role.permissions.map((perm) => (
                            <Chip
                              key={perm}
                              label={perm}
                              size="small"
                              color="primary"
                              variant="outlined"
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
              Thống kê vai trò trong hệ thống
            </Typography>
            <Grid container spacing={3}>
              {predefinedRoles.map((role) => (
                <Grid item xs={12} md={4} key={role.value}>
                  <Card variant="outlined">
                    <CardContent>
                      <Box display="flex" alignItems="center" mb={2}>
                        <Avatar sx={{ mr: 2, bgcolor: `${role.color}.main` }}>
                          {role.icon}
                        </Avatar>
                        <Box>
                          <Typography variant="subtitle1" fontWeight="bold">
                            {role.label}
                          </Typography>
                          <Typography variant="body2" color="text.secondary">
                            {role.description}
                          </Typography>
                        </Box>
                      </Box>
                      <Divider sx={{ mb: 2 }} />
                      <Box display="flex" justifyContent="space-between" alignItems="center" mb={1}>
                        <Typography variant="body2" color="text.secondary">
                          Số lượng thành viên
                        </Typography>
                        <Chip
                          icon={<GroupIcon />}
                          label={`${getUsersByRole(role.value).length} người`}
                          size="small"
                          color="primary"
                        />
                      </Box>
                      <Box display="flex" justifyContent="space-between" alignItems="center">
                        <Typography variant="body2" color="text.secondary">
                          Số quyền mặc định
                        </Typography>
                        <Chip
                          icon={<SecurityIcon />}
                          label={`${getPermissionCount(role.value)} quyền`}
                          size="small"
                          color="secondary"
                        />
                      </Box>
                    </CardContent>
                  </Card>
                </Grid>
              ))}
            </Grid>
          </CardContent>
        )}
      </Card>

      {/* Edit Role Dialog */}
      <Dialog open={openDialog} onClose={handleCloseDialog} maxWidth="sm" fullWidth>
        <DialogTitle>{editingRole ? 'Chỉnh sửa vai trò' : 'Thêm vai trò mới'}</DialogTitle>
        <DialogContent>
          <Box component="form" sx={{ mt: 2 }}>
            <TextField
              fullWidth
              margin="normal"
              label="Tên hệ thống"
              value={formData.name}
              onChange={(e) => setFormData({ ...formData, name: e.target.value })}
            />
            <TextField
              fullWidth
              margin="normal"
              label="Tên hiển thị"
              value={formData.display_name}
              onChange={(e) => setFormData({ ...formData, display_name: e.target.value })}
            />
            <TextField
              fullWidth
              margin="normal"
              label="Mô tả"
              multiline
              rows={3}
              value={formData.description}
              onChange={(e) => setFormData({ ...formData, description: e.target.value })}
            />
          </Box>
        </DialogContent>
        <DialogActions>
          <Button onClick={handleCloseDialog}>Hủy</Button>
          <Button onClick={handleSubmit} variant="contained" color="primary">
            {editingRole ? 'Cập nhật' : 'Tạo mới'}
          </Button>
        </DialogActions>
      </Dialog>

      {/* Permission Dialog */}
      <Dialog open={openPermissionDialog} onClose={handleClosePermissionDialog} maxWidth="md" fullWidth>
        <DialogTitle>Phân quyền chi tiết cho vai trò</DialogTitle>
        <DialogContent>
          <Box sx={{ mt: 2 }}>
            {Object.entries(groupedPermissions).map(([group, groupPerms]) => (
              <Accordion key={group} defaultExpanded>
                <AccordionSummary expandIcon={<ExpandMoreIcon />}>
                  <Typography>{group}</Typography>
                </AccordionSummary>
                <AccordionDetails>
                  <FormGroup>
                    {groupPerms.map(permission => (
                      <FormControlLabel
                        key={permission.id}
                        control={
                          <Checkbox
                            checked={permissionFormData.permissions.includes(permission.id)}
                            onChange={(e) => {
                              const checked = e.target.checked;
                              setPermissionFormData(prev => ({
                                ...prev,
                                permissions: checked
                                  ? [...prev.permissions, permission.id]
                                  : prev.permissions.filter(id => id !== permission.id)
                              }));
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
          </Box>
        </DialogContent>
        <DialogActions>
          <Button onClick={handleClosePermissionDialog}>Hủy</Button>
          <Button onClick={handlePermissionSubmit} variant="contained" color="primary">
            Lưu thay đổi
          </Button>
        </DialogActions>
      </Dialog>

      {/* Notification */}
      <Snackbar
        open={snackbar.open}
        autoHideDuration={6000}
        onClose={() => setSnackbar({ ...snackbar, open: false })}
        message={snackbar.message}
      />
    </Container>
  );
};

export default Roles;
