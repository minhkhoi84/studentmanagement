import React, { useState, useEffect, useCallback } from 'react';
import { useAuth } from '../contexts/AuthContext';
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
  Avatar,
  Divider,
  Checkbox,
  FormControlLabel,
  FormGroup,
  Accordion,
  AccordionSummary,
  AccordionDetails
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
  ExpandMore as ExpandMoreIcon,
  Group as GroupIcon,
  Shield as ShieldIcon
} from '@mui/icons-material';

const Users = () => {
  const { isSuperAdmin, hasPermission } = useAuth();
  const [users, setUsers] = useState([]);
  const [permissions, setPermissions] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [searchTerm, setSearchTerm] = useState('');
  const [roleFilter, setRoleFilter] = useState('');
  const [openDialog, setOpenDialog] = useState(false);
  const [openPermissionDialog, setOpenPermissionDialog] = useState(false);
  const [editingUser, setEditingUser] = useState(null);
  const [snackbar, setSnackbar] = useState({ open: false, message: '', severity: 'success' });

  // Form state
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: 'user',
    permissions: []
  });

  // Permission form state
  const [permissionFormData, setPermissionFormData] = useState({
    user_id: '',
    permissions: []
  });

  const fetchUsers = useCallback(async () => {
    try {
      setLoading(true);
      const params = new URLSearchParams();
      if (searchTerm) params.append('search', searchTerm);
      if (roleFilter) params.append('role', roleFilter);

      const response = await fetch(`http://127.0.0.1:8000/api/users?${params}`);
      if (!response.ok) throw new Error('Failed to fetch users');
      const data = await response.json();
      setUsers(data.data || data || []);
    } catch (err) {
      setError(err.message);
    } finally {
      setLoading(false);
    }
  }, [searchTerm, roleFilter]);

  const fetchPermissions = useCallback(async () => {
    try {
      const response = await fetch('http://127.0.0.1:8000/api/permissions');
      if (!response.ok) throw new Error('Failed to fetch permissions');
      const data = await response.json();
      setPermissions(data.data || data || []);
    } catch (err) {
      console.error('Error fetching permissions:', err);
    }
  }, []);

  useEffect(() => {
    fetchUsers();
    fetchPermissions();
  }, [fetchUsers, fetchPermissions]);

  const handleOpenDialog = (userData = null) => {
    if (userData) {
      setEditingUser(userData);
      setFormData({
        name: userData.name || '',
        email: userData.email || '',
        password: '',
        password_confirmation: '',
        role: userData.role || 'user',
        permissions: userData.permissions?.map(p => p.id) || []
      });
    } else {
      setEditingUser(null);
      setFormData({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        role: 'user',
        permissions: []
      });
    }
    setOpenDialog(true);
  };

  const handleCloseDialog = () => {
    setOpenDialog(false);
    setEditingUser(null);
    setFormData({
      name: '',
      email: '',
      password: '',
      password_confirmation: '',
      role: 'user',
      permissions: []
    });
  };

  const handleOpenPermissionDialog = (userData) => {
    setPermissionFormData({
      user_id: userData.id,
      permissions: userData.permissions?.map(p => p.id) || []
    });
    setOpenPermissionDialog(true);
  };

  const handleClosePermissionDialog = () => {
    setOpenPermissionDialog(false);
    setPermissionFormData({
      user_id: '',
      permissions: []
    });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      const url = editingUser 
        ? `http://127.0.0.1:8000/api/users/${editingUser.id}`
        : 'http://127.0.0.1:8000/api/users';
      
      const method = editingUser ? 'PUT' : 'POST';
      
      const submitData = {
        name: formData.name,
        email: formData.email,
        role: formData.role,
        permissions: formData.permissions
      };

      // Chỉ thêm password nếu đang tạo mới hoặc có nhập password
      if (!editingUser || formData.password) {
        submitData.password = formData.password;
        submitData.password_confirmation = formData.password_confirmation;
      }
      
      const response = await fetch(url, {
        method,
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(submitData),
      });

      if (!response.ok) {
        const errorData = await response.json();
        throw new Error(errorData.message || 'Failed to save user');
      }

      setSnackbar({
        open: true,
        message: editingUser ? 'Thành viên đã được cập nhật thành công!' : 'Thành viên đã được tạo thành công!',
        severity: 'success'
      });

      handleCloseDialog();
      fetchUsers();
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
      const response = await fetch(`http://127.0.0.1:8000/api/users/${permissionFormData.user_id}/permissions`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          permissions: permissionFormData.permissions
        }),
      });

      if (!response.ok) {
        const errorData = await response.json();
        throw new Error(errorData.message || 'Failed to update permissions');
      }

      setSnackbar({
        open: true,
        message: 'Quyền hạn đã được cập nhật thành công!',
        severity: 'success'
      });

      handleClosePermissionDialog();
      fetchUsers();
    } catch (err) {
      setSnackbar({
        open: true,
        message: 'Có lỗi xảy ra: ' + err.message,
        severity: 'error'
      });
    }
  };

  const handleDelete = async (userId, userName) => {
    if (window.confirm(`Bạn có chắc muốn xóa thành viên "${userName}" không?`)) {
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
          message: 'Thành viên đã được xóa thành công!',
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

  const getRoleIcon = (role) => {
    switch (role) {
      case 'super_admin': return <AdminIcon color="error" />;
      case 'admin': return <ShieldIcon color="warning" />;
      case 'user': return <PersonIcon color="primary" />;
      default: return <PersonIcon />;
    }
  };

  const getRoleText = (role) => {
    switch (role) {
      case 'super_admin': return 'Super Admin';
      case 'admin': return 'Admin';
      case 'user': return 'User';
      default: return 'Không xác định';
    }
  };

  const getRoleColor = (role) => {
    switch (role) {
      case 'super_admin': return 'error';
      case 'admin': return 'warning';
      case 'user': return 'primary';
      default: return 'default';
    }
  };

  const handlePermissionChange = (permissionId, checked) => {
    if (checked) {
      setFormData(prev => ({
        ...prev,
        permissions: [...prev.permissions, permissionId]
      }));
    } else {
      setFormData(prev => ({
        ...prev,
        permissions: prev.permissions.filter(id => id !== permissionId)
      }));
    }
  };

  const handlePermissionFormChange = (permissionId, checked) => {
    if (checked) {
      setPermissionFormData(prev => ({
        ...prev,
        permissions: [...prev.permissions, permissionId]
      }));
    } else {
      setPermissionFormData(prev => ({
        ...prev,
        permissions: prev.permissions.filter(id => id !== permissionId)
      }));
    }
  };

  const groupedPermissions = permissions.reduce((acc, permission) => {
    const group = permission.group || 'Khác';
    if (!acc[group]) {
      acc[group] = [];
    }
    acc[group].push(permission);
    return acc;
  }, {});

  // Chỉ filter theo role ở frontend, search đã được xử lý ở backend
  const filteredUsers = users.filter(user => {
    const matchesRole = !roleFilter || user.role === roleFilter;
    
    return matchesRole;
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
            <GroupIcon sx={{ mr: 2, verticalAlign: 'middle' }} />
            Quản Lý Thành Viên
          </Typography>
          <Typography variant="subtitle1" color="text.secondary">
            Quản lý tài khoản và quyền hạn của thành viên trong hệ thống
          </Typography>
        </Box>
        {(isSuperAdmin() || hasPermission('create-users')) && (
          <Button
            variant="contained"
            color="primary"
            startIcon={<AddIcon />}
            onClick={() => handleOpenDialog()}
          >
            Thêm thành viên
          </Button>
        )}
      </Box>

      {/* Search and Filter Section */}
      <Card sx={{ mb: 3 }}>
        <CardContent>
          <Grid container spacing={2} alignItems="center">
            <Grid item xs={12} md={6}>
              <TextField
                fullWidth
                label="Tìm kiếm"
                placeholder="Tên, email, vai trò..."
                value={searchTerm}
                onChange={(e) => setSearchTerm(e.target.value)}
                InputProps={{
                  startAdornment: <SearchIcon sx={{ mr: 1, color: 'text.secondary' }} />
                }}
              />
            </Grid>
            <Grid item xs={12} md={3}>
              <FormControl fullWidth>
                <InputLabel>Vai trò</InputLabel>
                <Select
                  value={roleFilter}
                  label="Vai trò"
                  onChange={(e) => setRoleFilter(e.target.value)}
                >
                  <MenuItem value="">Tất cả</MenuItem>
                  <MenuItem value="super_admin">Super Admin</MenuItem>
                  <MenuItem value="admin">Admin</MenuItem>
                  <MenuItem value="user">User</MenuItem>
                </Select>
              </FormControl>
            </Grid>
            <Grid item xs={12} md={3}>
              <Box display="flex" justifyContent="flex-end" gap={2}>
                <Button
                  variant="outlined"
                  startIcon={<FilterIcon />}
                  onClick={fetchUsers}
                >
                  Lọc
                </Button>
                <Button
                  variant="outlined"
                  onClick={() => {
                    setSearchTerm('');
                    setRoleFilter('');
                  }}
                >
                  Đặt lại
                </Button>
              </Box>
            </Grid>
          </Grid>
        </CardContent>
      </Card>

      {/* Users Table */}
      <Card>
        <CardContent>
          <Box display="flex" justifyContent="space-between" alignItems="center" mb={2}>
            <Typography variant="h6">
              Danh sách thành viên ({filteredUsers.length} thành viên)
            </Typography>
          </Box>
          
          <TableContainer component={Paper} variant="outlined">
            <Table>
              <TableHead>
                <TableRow>
                  <TableCell>#</TableCell>
                  <TableCell>Thành viên</TableCell>
                  <TableCell>Vai trò</TableCell>
                  <TableCell>Quyền hạn</TableCell>
                  <TableCell>Ngày tạo</TableCell>
                  <TableCell align="center">Thao tác</TableCell>
                </TableRow>
              </TableHead>
              <TableBody>
                {filteredUsers.length === 0 ? (
                  <TableRow>
                    <TableCell colSpan={6} align="center" sx={{ py: 4 }}>
                      <Typography color="text.secondary">
                        Không tìm thấy thành viên nào
                      </Typography>
                    </TableCell>
                  </TableRow>
                ) : (
                  filteredUsers.map((user, index) => (
                    <TableRow key={user.id} hover>
                      <TableCell>{index + 1}</TableCell>
                      <TableCell>
                        <Box display="flex" alignItems="center">
                          <Avatar sx={{ mr: 2, bgcolor: 'primary.main' }}>
                            {user.name?.charAt(0).toUpperCase() || <PersonIcon />}
                          </Avatar>
                          <Box>
                            <Typography variant="subtitle2" fontWeight="bold">
                              {user.name}
                            </Typography>
                            <Typography variant="caption" color="text.secondary">
                              {user.email}
                            </Typography>
                          </Box>
                        </Box>
                      </TableCell>
                      <TableCell>
                        <Chip
                          icon={getRoleIcon(user.role)}
                          label={getRoleText(user.role)}
                          color={getRoleColor(user.role)}
                          size="small"
                        />
                      </TableCell>
                      <TableCell>
                        {user.permissions && user.permissions.length > 0 ? (
                          <Typography variant="body2">
                            {user.permissions.map(p => p.name).join(', ')}
                          </Typography>
                        ) : (
                          <Typography variant="body2" color="text.secondary">
                            Chưa được phân quyền
                          </Typography>
                        )}
                      </TableCell>
                      <TableCell>
                        {new Date(user.created_at).toLocaleDateString('vi-VN')}
                      </TableCell>
                      <TableCell align="center">
                        <Box display="flex" justifyContent="center" gap={1}>
                          {(isSuperAdmin() || hasPermission('edit-users')) && (
                            <IconButton
                              size="small"
                              color="primary"
                              onClick={() => handleOpenDialog(user)}
                            >
                              <EditIcon />
                            </IconButton>
                          )}
                          {(isSuperAdmin() || hasPermission('manage-user-permissions')) && (
                            <IconButton
                              size="small"
                              color="secondary"
                              onClick={() => handleOpenPermissionDialog(user)}
                            >
                              <SecurityIcon />
                            </IconButton>
                          )}
                          {isSuperAdmin() && (
                            <IconButton
                              size="small"
                              color="error"
                              onClick={() => handleDelete(user.id, user.name)}
                            >
                              <DeleteIcon />
                            </IconButton>
                          )}
                        </Box>
                      </TableCell>
                    </TableRow>
                  ))
                )}
              </TableBody>
            </Table>
          </TableContainer>
        </CardContent>
      </Card>

      {/* User Dialog */}
      <Dialog open={openDialog} onClose={handleCloseDialog} maxWidth="sm" fullWidth>
        <DialogTitle>{editingUser ? 'Chỉnh sửa thành viên' : 'Thêm thành viên mới'}</DialogTitle>
        <DialogContent>
          <Box component="form" sx={{ mt: 2 }}>
            <Grid container spacing={2}>
              <Grid item xs={12}>
                <TextField
                  fullWidth
                  label="Họ và tên"
                  value={formData.name}
                  onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                />
              </Grid>
              <Grid item xs={12}>
                <TextField
                  fullWidth
                  label="Email"
                  type="email"
                  value={formData.email}
                  onChange={(e) => setFormData({ ...formData, email: e.target.value })}
                />
              </Grid>
              <Grid item xs={12}>
                <FormControl fullWidth>
                  <InputLabel>Vai trò</InputLabel>
                  <Select
                    value={formData.role}
                    label="Vai trò"
                    onChange={(e) => setFormData({ ...formData, role: e.target.value })}
                  >
                    <MenuItem value="super_admin">Super Admin</MenuItem>
                    <MenuItem value="admin">Admin</MenuItem>
                    <MenuItem value="user">User</MenuItem>
                  </Select>
                </FormControl>
              </Grid>
              <Grid item xs={12}>
                <TextField
                  fullWidth
                  label="Mật khẩu"
                  type="password"
                  value={formData.password}
                  onChange={(e) => setFormData({ ...formData, password: e.target.value })}
                />
              </Grid>
              <Grid item xs={12}>
                <TextField
                  fullWidth
                  label="Xác nhận mật khẩu"
                  type="password"
                  value={formData.password_confirmation}
                  onChange={(e) => setFormData({ ...formData, password_confirmation: e.target.value })}
                />
              </Grid>
            </Grid>

            <Box mt={3}>
              <Typography variant="subtitle1" gutterBottom>
                Quyền hạn trực tiếp
              </Typography>
              <Divider sx={{ mb: 2 }} />
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
                              checked={formData.permissions.includes(permission.id)}
                              onChange={(e) => handlePermissionChange(permission.id, e.target.checked)}
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
          </Box>
        </DialogContent>
        <DialogActions>
          <Button onClick={handleCloseDialog}>Hủy</Button>
          <Button onClick={handleSubmit} variant="contained" color="primary">
            {editingUser ? 'Cập nhật' : 'Tạo mới'}
          </Button>
        </DialogActions>
      </Dialog>

      {/* Permission Dialog */}
      <Dialog open={openPermissionDialog} onClose={handleClosePermissionDialog} maxWidth="md" fullWidth>
        <DialogTitle>Phân quyền chi tiết</DialogTitle>
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
                            onChange={(e) => handlePermissionFormChange(permission.id, e.target.checked)}
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

export default Users;
