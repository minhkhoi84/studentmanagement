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
  DialogActions,
  DialogContent,
  DialogTitle,
  Snackbar
} from '@mui/material';
import {
  Add as AddIcon,
  Edit as EditIcon,
  Delete as DeleteIcon,
  Search as SearchIcon,
  Refresh as RefreshIcon,
  Business as BusinessIcon,
  Code as CodeIcon,
  Description as DescriptionIcon
} from '@mui/icons-material';
import { useAuth } from '../contexts/AuthContext';

const Departments = () => {
  const { isSuperAdmin, hasPermission } = useAuth();
  const [departments, setDepartments] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [searchTerm, setSearchTerm] = useState('');
  const [openDialog, setOpenDialog] = useState(false);
  const [editingDepartment, setEditingDepartment] = useState(null);
  const [snackbar, setSnackbar] = useState({ open: false, message: '', severity: 'success' });

  const [formData, setFormData] = useState({
    name: '',
    code: '',
    description: '',
    status: 'active'
  });

  useEffect(() => {
    fetchDepartments();
  }, [searchTerm]);

  const fetchDepartments = async () => {
    try {
      setLoading(true);
      const params = new URLSearchParams();
      if (searchTerm) params.append('search', searchTerm);

      const response = await fetch(`http://127.0.0.1:8000/api/departments?${params}`);
      if (!response.ok) throw new Error('Failed to fetch departments');
      const data = await response.json();
      setDepartments(data.data || data || []);
    } catch (err) {
      setError(err.message);
    } finally {
      setLoading(false);
    }
  };

  const handleOpenDialog = (departmentData = null) => {
    if (departmentData) {
      setEditingDepartment(departmentData);
      setFormData({
        name: departmentData.name || '',
        code: departmentData.code || '',
        description: departmentData.description || '',
        status: departmentData.status || 'active'
      });
    } else {
      setEditingDepartment(null);
      setFormData({
        name: '',
        code: '',
        description: '',
        status: 'active'
      });
    }
    setOpenDialog(true);
  };

  const handleCloseDialog = () => {
    setOpenDialog(false);
    setEditingDepartment(null);
    setFormData({
      name: '',
      code: '',
      description: '',
      status: 'active'
    });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      const url = editingDepartment 
        ? `http://127.0.0.1:8000/api/departments/${editingDepartment.id}`
        : 'http://127.0.0.1:8000/api/departments';
      
      const method = editingDepartment ? 'PUT' : 'POST';
      
      const response = await fetch(url, {
        method,
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(formData),
      });

      const data = await response.json();

      if (!response.ok) {
        // Hiển thị lỗi validation chi tiết
        let errorMessage = 'Có lỗi xảy ra khi lưu khoa';
        if (data.errors) {
          errorMessage = Object.values(data.errors).flat().join(', ');
        } else if (data.message) {
          errorMessage = data.message;
        }
        throw new Error(errorMessage);
      }

      setSnackbar({
        open: true,
        message: editingDepartment ? 'Khoa đã được cập nhật thành công!' : 'Khoa đã được tạo thành công!',
        severity: 'success'
      });

      handleCloseDialog();
      fetchDepartments();
    } catch (err) {
      setSnackbar({
        open: true,
        message: err.message,
        severity: 'error'
      });
    }
  };

  const handleDelete = async (departmentId, departmentName) => {
    if (window.confirm(`Bạn có chắc muốn xóa khoa "${departmentName}" không?`)) {
      try {
        const response = await fetch(`http://127.0.0.1:8000/api/departments/${departmentId}`, {
          method: 'DELETE',
        });

        if (!response.ok) {
          const errorData = await response.json();
          let errorMessage = errorData.message || 'Không thể xóa khoa';
          
          // Xử lý thông báo lỗi tiếng Việt
          if (errorMessage.includes('existing teachers')) {
            errorMessage = 'Không thể xóa khoa này vì đang có giảng viên thuộc khoa. Vui lòng chuyển hoặc xóa giảng viên trước.';
          } else if (errorMessage.includes('existing students')) {
            errorMessage = 'Không thể xóa khoa này vì đang có sinh viên thuộc khoa. Vui lòng chuyển hoặc xóa sinh viên trước.';
          } else if (errorMessage.includes('existing classes')) {
            errorMessage = 'Không thể xóa khoa này vì đang có lớp học thuộc khoa. Vui lòng xóa các lớp học trước.';
          }
          
          throw new Error(errorMessage);
        }

        setSnackbar({
          open: true,
          message: 'Khoa đã được xóa thành công!',
          severity: 'success'
        });

        fetchDepartments();
      } catch (err) {
        setSnackbar({
          open: true,
          message: err.message,
          severity: 'error'
        });
      }
    }
  };

  const getStatusColor = (status) => {
    switch (status) {
      case 'active': return 'success';
      case 'inactive': return 'error';
      default: return 'default';
    }
  };

  const getStatusText = (status) => {
    switch (status) {
      case 'active': return 'Hoạt động';
      case 'inactive': return 'Không hoạt động';
      default: return 'Không xác định';
    }
  };

  // Không cần filter ở frontend vì đã search ở backend
  const filteredDepartments = departments;

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
            <BusinessIcon sx={{ mr: 2, verticalAlign: 'middle' }} />
            Quản Lý Khoa
          </Typography>
          <Typography variant="subtitle1" color="text.secondary">
            Quản lý thông tin các khoa trong hệ thống
          </Typography>
        </Box>
        {(isSuperAdmin() || hasPermission('them-moi-khoa')) && (
          <Button
            variant="contained"
            color="primary"
            startIcon={<AddIcon />}
            onClick={() => handleOpenDialog()}
          >
            Thêm khoa
          </Button>
        )}
      </Box>

      {/* Search Section */}
      <Card sx={{ mb: 3 }}>
        <CardContent>
          <Grid container spacing={2} alignItems="center">
            <Grid item xs={12} md={8}>
              <TextField
                fullWidth
                label="Tìm kiếm"
                placeholder="Tên khoa, mã khoa, mô tả..."
                value={searchTerm}
                onChange={(e) => setSearchTerm(e.target.value)}
                InputProps={{
                  startAdornment: <SearchIcon sx={{ mr: 1, color: 'text.secondary' }} />
                }}
              />
            </Grid>
            <Grid item xs={12} md={4}>
              <Box display="flex" gap={2}>
                <Button
                  variant="outlined"
                  startIcon={<RefreshIcon />}
                  onClick={fetchDepartments}
                >
                  Làm mới
                </Button>
                <Button
                  variant="outlined"
                  onClick={() => setSearchTerm('')}
                >
                  Đặt lại
                </Button>
              </Box>
            </Grid>
          </Grid>
        </CardContent>
      </Card>

      {/* Departments List */}
      <Card>
        <CardContent>
          <Box display="flex" justifyContent="space-between" alignItems="center" mb={2}>
            <Typography variant="h6">
              Danh sách khoa ({filteredDepartments.length} khoa)
            </Typography>
          </Box>
          
          <TableContainer component={Paper} variant="outlined">
            <Table>
              <TableHead>
                <TableRow>
                  <TableCell>#</TableCell>
                  <TableCell>Thông tin khoa</TableCell>
                  <TableCell>Mã khoa</TableCell>
                  <TableCell>Mô tả</TableCell>
                  <TableCell>Trạng thái</TableCell>
                  <TableCell>Ngày tạo</TableCell>
                  <TableCell align="center">Thao tác</TableCell>
                </TableRow>
              </TableHead>
              <TableBody>
                {filteredDepartments.length === 0 ? (
                  <TableRow>
                    <TableCell colSpan={7} align="center" sx={{ py: 4 }}>
                      <Box textAlign="center">
                        <BusinessIcon sx={{ fontSize: 48, color: 'text.secondary', mb: 2 }} />
                        <Typography variant="h6" color="text.secondary">
                          Không tìm thấy khoa nào
                        </Typography>
                        <Typography variant="body2" color="text.secondary">
                          Hãy thử thay đổi bộ lọc hoặc thêm khoa mới
                        </Typography>
                      </Box>
                    </TableCell>
                  </TableRow>
                ) : (
                  filteredDepartments.map((department, index) => (
                    <TableRow key={department.id} hover>
                      <TableCell>{index + 1}</TableCell>
                      <TableCell>
                        <Box display="flex" alignItems="center">
                          <BusinessIcon sx={{ mr: 2, color: 'primary.main' }} />
                          <Box>
                            <Typography variant="subtitle2" fontWeight="bold">
                              {department.name}
                            </Typography>
                            <Typography variant="caption" color="text.secondary">
                              ID: #{department.id}
                            </Typography>
                          </Box>
                        </Box>
                      </TableCell>
                      <TableCell>
                        <Box display="flex" alignItems="center">
                          <CodeIcon sx={{ mr: 1, fontSize: 16, color: 'text.secondary' }} />
                          <Typography variant="body2" fontWeight="bold">
                            {department.code}
                          </Typography>
                        </Box>
                      </TableCell>
                      <TableCell>
                        <Box display="flex" alignItems="center">
                          <DescriptionIcon sx={{ mr: 1, fontSize: 16, color: 'text.secondary' }} />
                          <Typography variant="body2">
                            {department.description || 'Chưa có mô tả'}
                          </Typography>
                        </Box>
                      </TableCell>
                      <TableCell>
                        <Chip 
                          label={getStatusText(department.status)}
                          color={getStatusColor(department.status)}
                          size="small"
                        />
                      </TableCell>
                      <TableCell>
                        {new Date(department.created_at).toLocaleDateString('vi-VN')}
                      </TableCell>
                      <TableCell align="center">
                        <Box display="flex" gap={1} justifyContent="center">
                          {(isSuperAdmin() || hasPermission('chinh-sua-khoa')) && (
                            <IconButton
                              size="small"
                              color="primary"
                              onClick={() => handleOpenDialog(department)}
                              title="Chỉnh sửa"
                            >
                              <EditIcon />
                            </IconButton>
                          )}
                          {(isSuperAdmin() || hasPermission('xoa-khoa')) && (
                            <IconButton
                              size="small"
                              color="error"
                              onClick={() => handleDelete(department.id, department.name)}
                              title="Xóa khoa"
                            >
                              <DeleteIcon />
                            </IconButton>
                          )}
                          {!isSuperAdmin() && !hasPermission('chinh-sua-khoa') && !hasPermission('xoa-khoa') && (
                            <Typography variant="caption" color="text.secondary">
                              Không có quyền
                            </Typography>
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

      {/* Add/Edit Dialog */}
      <Dialog open={openDialog} onClose={handleCloseDialog} maxWidth="md" fullWidth>
        <DialogTitle>
          {editingDepartment ? 'Chỉnh sửa khoa' : 'Thêm khoa mới'}
        </DialogTitle>
        <form onSubmit={handleSubmit}>
          <DialogContent>
            <Grid container spacing={2} sx={{ mt: 1 }}>
              <Grid item xs={12} sm={6}>
                <TextField
                  fullWidth
                  label="Tên khoa"
                  value={formData.name}
                  onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                  required
                />
              </Grid>
              <Grid item xs={12} sm={6}>
                <TextField
                  fullWidth
                  label="Mã khoa"
                  value={formData.code}
                  onChange={(e) => setFormData({ ...formData, code: e.target.value })}
                  required
                />
              </Grid>
              <Grid item xs={12} sm={6}>
                <FormControl fullWidth>
                  <InputLabel>Trạng thái</InputLabel>
                  <Select
                    value={formData.status}
                    label="Trạng thái"
                    onChange={(e) => setFormData({ ...formData, status: e.target.value })}
                    required
                  >
                    <MenuItem value="active">Hoạt động</MenuItem>
                    <MenuItem value="inactive">Không hoạt động</MenuItem>
                  </Select>
                </FormControl>
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
              {editingDepartment ? 'Cập nhật' : 'Tạo khoa'}
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

export default Departments;
