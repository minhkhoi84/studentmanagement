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
  Snackbar
} from '@mui/material';
import {
  Add as AddIcon,
  Edit as EditIcon,
  Delete as DeleteIcon,
  Search as SearchIcon,
  FilterList as FilterIcon,
  School as SchoolIcon,
  Business as BusinessIcon,
  Visibility as ViewIcon
} from '@mui/icons-material';
import { useAuth } from '../contexts/AuthContext';

const Classes = () => {
  const { isSuperAdmin, hasPermission } = useAuth();
  const [classes, setClasses] = useState([]);
  const [departments, setDepartments] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [searchTerm, setSearchTerm] = useState('');
  const [departmentFilter, setDepartmentFilter] = useState('');
  const [statusFilter, setStatusFilter] = useState('');
  const [openDialog, setOpenDialog] = useState(false);
  const [editingClass, setEditingClass] = useState(null);
  const [snackbar, setSnackbar] = useState({ open: false, message: '', severity: 'success' });

  // Form state
  const [formData, setFormData] = useState({
    name: '',
    class_code: '',
    department_id: '',
    max_students: '',
    status: 'active'
  });

  useEffect(() => {
    fetchClasses();
    fetchDepartments();
  }, [searchTerm]);

  const fetchClasses = async () => {
    try {
      setLoading(true);
      const response = await fetch('http://127.0.0.1:8000/api/classes');
      if (!response.ok) throw new Error('Failed to fetch classes');
      const data = await response.json();
      setClasses(data.data || data || []);
    } catch (err) {
      setError(err.message);
    } finally {
      setLoading(false);
    }
  };

  const fetchDepartments = async () => {
    try {
      const response = await fetch('http://127.0.0.1:8000/api/departments');
      if (!response.ok) throw new Error('Failed to fetch departments');
      const data = await response.json();
      setDepartments(data.data || data || []);
    } catch (err) {
      console.error('Error fetching departments:', err);
    }
  };

  const handleOpenDialog = (classData = null) => {
    if (classData) {
      setEditingClass(classData);
      setFormData({
        name: classData.name || '',
        class_code: classData.class_code || classData.code || '',
        department_id: classData.department_id || '',
        max_students: classData.max_students || '',
        status: classData.status || 'active'
      });
    } else {
      setEditingClass(null);
      setFormData({
        name: '',
        class_code: '',
        department_id: '',
        max_students: '',
        status: 'active'
      });
    }
    setOpenDialog(true);
  };

  const handleCloseDialog = () => {
    setOpenDialog(false);
    setEditingClass(null);
    setFormData({
      name: '',
      class_code: '',
      department_id: '',
      max_students: '',
      status: 'active'
    });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      const url = editingClass 
        ? `http://127.0.0.1:8000/api/classes/${editingClass.id}`
        : 'http://127.0.0.1:8000/api/classes';
      
      const method = editingClass ? 'PUT' : 'POST';
      
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
        let errorMessage = 'Có lỗi xảy ra khi lưu lớp';
        if (data.errors) {
          errorMessage = Object.values(data.errors).flat().join(', ');
        } else if (data.message) {
          errorMessage = data.message;
        }
        throw new Error(errorMessage);
      }

      setSnackbar({
        open: true,
        message: editingClass ? 'Lớp đã được cập nhật thành công!' : 'Lớp đã được tạo thành công!',
        severity: 'success'
      });

      handleCloseDialog();
      fetchClasses();
    } catch (err) {
      setSnackbar({
        open: true,
        message: err.message,
        severity: 'error'
      });
    }
  };

  const handleDelete = async (classId, className) => {
    if (window.confirm(`Bạn có chắc muốn xóa lớp "${className}" không?`)) {
      try {
        const response = await fetch(`http://127.0.0.1:8000/api/classes/${classId}`, {
          method: 'DELETE',
        });

        if (!response.ok) throw new Error('Failed to delete class');

        setSnackbar({
          open: true,
          message: 'Lớp đã được xóa thành công!',
          severity: 'success'
        });

        fetchClasses();
      } catch (err) {
        setSnackbar({
          open: true,
          message: 'Có lỗi xảy ra: ' + err.message,
          severity: 'error'
        });
      }
    }
  };

  // Chỉ filter theo department và status ở frontend, search đã được xử lý ở backend
  const filteredClasses = classes.filter(classItem => {
    const matchesDepartment = !departmentFilter || classItem.department_id == departmentFilter;
    const matchesStatus = !statusFilter || classItem.status === statusFilter;
    
    return matchesDepartment && matchesStatus;
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
            <SchoolIcon sx={{ mr: 2, verticalAlign: 'middle' }} />
            Quản Lý Lớp
          </Typography>
          <Typography variant="subtitle1" color="text.secondary">
            Quản lý thông tin các lớp học trong hệ thống
          </Typography>
        </Box>
        {(isSuperAdmin() || hasPermission('them-moi-lop')) && (
          <Button
            variant="contained"
            color="primary"
            startIcon={<AddIcon />}
            onClick={() => handleOpenDialog()}
            sx={{ height: 'fit-content' }}
          >
            Thêm lớp mới
          </Button>
        )}
      </Box>

      {/* Search and Filter Section */}
      <Card sx={{ mb: 3 }}>
        <CardContent>
          <Grid container spacing={2} alignItems="center">
            <Grid item xs={12} md={4}>
              <TextField
                fullWidth
                label="Tìm kiếm"
                placeholder="Tên lớp, mã lớp..."
                value={searchTerm}
                onChange={(e) => setSearchTerm(e.target.value)}
                InputProps={{
                  startAdornment: <SearchIcon sx={{ mr: 1, color: 'text.secondary' }} />
                }}
              />
            </Grid>
            <Grid item xs={12} md={3}>
              <FormControl fullWidth>
                <InputLabel>Khoa</InputLabel>
                <Select
                  value={departmentFilter}
                  label="Khoa"
                  onChange={(e) => setDepartmentFilter(e.target.value)}
                >
                  <MenuItem value="">Tất cả khoa</MenuItem>
                  {departments.map((dept) => (
                    <MenuItem key={dept.id} value={dept.id}>
                      {dept.name}
                    </MenuItem>
                  ))}
                </Select>
              </FormControl>
            </Grid>
            <Grid item xs={12} md={3}>
              <FormControl fullWidth>
                <InputLabel>Trạng thái</InputLabel>
                <Select
                  value={statusFilter}
                  label="Trạng thái"
                  onChange={(e) => setStatusFilter(e.target.value)}
                >
                  <MenuItem value="">Tất cả</MenuItem>
                  <MenuItem value="active">Hoạt động</MenuItem>
                  <MenuItem value="inactive">Không hoạt động</MenuItem>
                </Select>
              </FormControl>
            </Grid>
            <Grid item xs={12} md={2}>
              <Button
                fullWidth
                variant="outlined"
                startIcon={<FilterIcon />}
                onClick={() => {
                  setSearchTerm('');
                  setDepartmentFilter('');
                  setStatusFilter('');
                }}
              >
                Đặt lại
              </Button>
            </Grid>
          </Grid>
        </CardContent>
      </Card>

      {/* Classes List */}
      <Card>
        <CardContent>
          <Box display="flex" justifyContent="space-between" alignItems="center" mb={2}>
            <Typography variant="h6">
              Danh sách lớp ({filteredClasses.length} lớp)
            </Typography>
          </Box>
          
          <TableContainer component={Paper} variant="outlined">
            <Table>
              <TableHead>
                <TableRow>
                  <TableCell>#</TableCell>
                  <TableCell>Mã Lớp</TableCell>
                  <TableCell>Tên Lớp</TableCell>
                  <TableCell>Khoa</TableCell>
                  <TableCell>Trạng Thái</TableCell>
                  <TableCell>Ngày Tạo</TableCell>
                  <TableCell align="center">Thao Tác</TableCell>
                </TableRow>
              </TableHead>
              <TableBody>
                {filteredClasses.length === 0 ? (
                  <TableRow>
                    <TableCell colSpan={7} align="center" sx={{ py: 4 }}>
                      <Box textAlign="center">
                        <SchoolIcon sx={{ fontSize: 48, color: 'text.secondary', mb: 2 }} />
                        <Typography variant="h6" color="text.secondary">
                          Không tìm thấy lớp nào
                        </Typography>
                        <Typography variant="body2" color="text.secondary">
                          Hãy thử thay đổi bộ lọc hoặc thêm lớp mới
                        </Typography>
                      </Box>
                    </TableCell>
                  </TableRow>
                ) : (
                  filteredClasses.map((classItem, index) => (
                    <TableRow key={classItem.id} hover>
                      <TableCell>{index + 1}</TableCell>
                      <TableCell>
                        <Chip 
                          label={classItem.code} 
                          color="primary" 
                          variant="outlined"
                          size="small"
                        />
                      </TableCell>
                      <TableCell>
                        <Box display="flex" alignItems="center">
                          <SchoolIcon sx={{ mr: 1, color: 'primary.main' }} />
                          <Box>
                            <Typography variant="subtitle2" fontWeight="bold">
                              {classItem.name}
                            </Typography>
                            <Typography variant="caption" color="text.secondary">
                              ID: #{classItem.id}
                            </Typography>
                          </Box>
                        </Box>
                      </TableCell>
                      <TableCell>
                        {classItem.department ? (
                          <Chip 
                            label={classItem.department.name}
                            color="info"
                            variant="outlined"
                            size="small"
                            icon={<BusinessIcon />}
                          />
                        ) : (
                          <Typography variant="body2" color="text.secondary">
                            Chưa phân khoa
                          </Typography>
                        )}
                      </TableCell>
                      <TableCell>
                        <Chip 
                          label={classItem.status === 'active' ? 'Hoạt động' : 'Không hoạt động'}
                          color={classItem.status === 'active' ? 'success' : 'default'}
                          size="small"
                        />
                      </TableCell>
                      <TableCell>
                        {new Date(classItem.created_at).toLocaleDateString('vi-VN')}
                      </TableCell>
                      <TableCell align="center">
                        <Box display="flex" gap={1} justifyContent="center">
                          <IconButton
                            size="small"
                            color="info"
                            onClick={() => handleOpenDialog(classItem)}
                            title="Xem chi tiết"
                          >
                            <ViewIcon />
                          </IconButton>
                          {(isSuperAdmin() || hasPermission('chinh-sua-lop')) && (
                            <IconButton
                              size="small"
                              color="primary"
                              onClick={() => handleOpenDialog(classItem)}
                              title="Chỉnh sửa"
                            >
                              <EditIcon />
                            </IconButton>
                          )}
                          {(isSuperAdmin() || hasPermission('xoa-lop')) && (
                            <IconButton
                              size="small"
                              color="error"
                              onClick={() => handleDelete(classItem.id, classItem.name)}
                              title="Xóa lớp"
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

      {/* Add/Edit Dialog */}
      <Dialog open={openDialog} onClose={handleCloseDialog} maxWidth="sm" fullWidth>
        <DialogTitle>
          {editingClass ? 'Chỉnh sửa lớp' : 'Thêm lớp mới'}
        </DialogTitle>
        <form onSubmit={handleSubmit}>
          <DialogContent>
            <Grid container spacing={2} sx={{ mt: 1 }}>
              <Grid item xs={12} sm={6}>
                <TextField
                  fullWidth
                  label="Tên lớp"
                  value={formData.name}
                  onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                  required
                  placeholder="VD: Công nghệ thông tin K1"
                />
              </Grid>
              <Grid item xs={12} sm={6}>
                <TextField
                  fullWidth
                  label="Mã lớp"
                  value={formData.class_code}
                  onChange={(e) => setFormData({ ...formData, class_code: e.target.value })}
                  required
                  placeholder="VD: CNTT01K1"
                />
              </Grid>
              <Grid item xs={12} sm={6}>
                <FormControl fullWidth required>
                  <InputLabel>Khoa *</InputLabel>
                  <Select
                    value={formData.department_id}
                    label="Khoa *"
                    onChange={(e) => setFormData({ ...formData, department_id: e.target.value })}
                    required
                  >
                    <MenuItem value="">Chọn khoa</MenuItem>
                    {departments.map((dept) => (
                      <MenuItem key={dept.id} value={dept.id}>
                        {dept.name}
                      </MenuItem>
                    ))}
                  </Select>
                </FormControl>
              </Grid>
              <Grid item xs={12} sm={6}>
                <TextField
                  fullWidth
                  label="Số sinh viên tối đa"
                  type="number"
                  value={formData.max_students}
                  onChange={(e) => setFormData({ ...formData, max_students: e.target.value })}
                  placeholder="VD: 40"
                />
              </Grid>
              <Grid item xs={12}>
                <FormControl fullWidth>
                  <InputLabel>Trạng thái</InputLabel>
                  <Select
                    value={formData.status}
                    label="Trạng thái"
                    onChange={(e) => setFormData({ ...formData, status: e.target.value })}
                  >
                    <MenuItem value="active">Hoạt động</MenuItem>
                    <MenuItem value="inactive">Không hoạt động</MenuItem>
                  </Select>
                </FormControl>
              </Grid>
            </Grid>
          </DialogContent>
          <DialogActions>
            <Button onClick={handleCloseDialog}>Hủy</Button>
            <Button type="submit" variant="contained">
              {editingClass ? 'Cập nhật' : 'Tạo lớp'}
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

export default Classes;
