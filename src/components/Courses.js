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
  Book as BookIcon,
  School as SchoolIcon,
  Person as PersonIcon,
  Schedule as ScheduleIcon,
  CreditCard as CreditCardIcon
} from '@mui/icons-material';

const Courses = () => {
  const [courses, setCourses] = useState([]);
  const [teachers, setTeachers] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [searchTerm, setSearchTerm] = useState('');
  const [openDialog, setOpenDialog] = useState(false);
  const [editingCourse, setEditingCourse] = useState(null);
  const [snackbar, setSnackbar] = useState({ open: false, message: '', severity: 'success' });

  const [formData, setFormData] = useState({
    name: '',
    code: '',
    description: '',
    credits: '',
    teacher_id: '',
    status: 'active'
  });

  useEffect(() => {
    fetchCourses();
    fetchTeachers();
  }, [searchTerm]);

  const fetchCourses = async () => {
    try {
      setLoading(true);
      const params = new URLSearchParams();
      if (searchTerm) params.append('search', searchTerm);

      const response = await fetch(`http://127.0.0.1:8000/api/courses?${params}`);
      if (!response.ok) throw new Error('Failed to fetch courses');
      const data = await response.json();
      setCourses(data.data || data || []);
    } catch (err) {
      setError(err.message);
    } finally {
      setLoading(false);
    }
  };

  const fetchTeachers = async () => {
    try {
      const response = await fetch('http://127.0.0.1:8000/api/teachers');
      if (!response.ok) throw new Error('Failed to fetch teachers');
      const data = await response.json();
      setTeachers(data.data || data || []);
    } catch (err) {
      console.error('Error fetching teachers:', err);
    }
  };

  const handleOpenDialog = (courseData = null) => {
    if (courseData) {
      setEditingCourse(courseData);
      setFormData({
        name: courseData.name || '',
        code: courseData.code || '',
        description: courseData.description || '',
        credits: courseData.credits || '',
        teacher_id: courseData.teacher_id || '',
        status: courseData.status || 'active'
      });
    } else {
      setEditingCourse(null);
      setFormData({
        name: '',
        code: '',
        description: '',
        credits: '',
        teacher_id: '',
        status: 'active'
      });
    }
    setOpenDialog(true);
  };

  const handleCloseDialog = () => {
    setOpenDialog(false);
    setEditingCourse(null);
    setFormData({
      name: '',
      code: '',
      description: '',
      credits: '',
      teacher_id: '',
      status: 'active'
    });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      const url = editingCourse 
        ? `http://127.0.0.1:8000/api/courses/${editingCourse.id}`
        : 'http://127.0.0.1:8000/api/courses';
      
      const method = editingCourse ? 'PUT' : 'POST';
      
      const response = await fetch(url, {
        method,
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(formData),
      });

      if (!response.ok) {
        const errorData = await response.json();
        throw new Error(errorData.message || 'Failed to save course');
      }

      setSnackbar({
        open: true,
        message: editingCourse ? 'Môn học đã được cập nhật thành công!' : 'Môn học đã được tạo thành công!',
        severity: 'success'
      });

      handleCloseDialog();
      fetchCourses();
    } catch (err) {
      setSnackbar({
        open: true,
        message: 'Có lỗi xảy ra: ' + err.message,
        severity: 'error'
      });
    }
  };

  const handleDelete = async (courseId, courseName) => {
    if (window.confirm(`Bạn có chắc muốn xóa môn học "${courseName}" không?`)) {
      try {
        const response = await fetch(`http://127.0.0.1:8000/api/courses/${courseId}`, {
          method: 'DELETE',
        });

        if (!response.ok) {
          const errorData = await response.json();
          throw new Error(errorData.message || 'Failed to delete course');
        }

        setSnackbar({
          open: true,
          message: 'Môn học đã được xóa thành công!',
          severity: 'success'
        });

        fetchCourses();
      } catch (err) {
        setSnackbar({
          open: true,
          message: 'Có lỗi xảy ra: ' + err.message,
          severity: 'error'
        });
      }
    }
  };

  const getStatusColor = (status) => {
    switch (status) {
      case 'active': return 'success';
      case 'inactive': return 'error';
      case 'archived': return 'info';
      default: return 'default';
    }
  };

  const getStatusText = (status) => {
    switch (status) {
      case 'active': return 'Đang mở';
      case 'inactive': return 'Tạm đóng';
      case 'archived': return 'Đã lưu trữ';
      default: return 'Không xác định';
    }
  };

  const getTeacherName = (teacherId) => {
    const teacher = teachers.find(t => t.id === teacherId);
    return teacher ? teacher.name : 'Chưa phân công';
  };

  // Không cần filter ở frontend vì đã search ở backend
  const filteredCourses = courses;

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
            <BookIcon sx={{ mr: 2, verticalAlign: 'middle' }} />
            Quản Lý Môn Học
          </Typography>
          <Typography variant="subtitle1" color="text.secondary">
            Quản lý thông tin môn học trong hệ thống
          </Typography>
        </Box>
        <Button
          variant="contained"
          color="primary"
          startIcon={<AddIcon />}
          onClick={() => handleOpenDialog()}
        >
          Thêm môn học
        </Button>
      </Box>

      {/* Search Section */}
      <Card sx={{ mb: 3 }}>
        <CardContent>
          <Grid container spacing={2} alignItems="center">
            <Grid item xs={12} md={8}>
              <TextField
                fullWidth
                label="Tìm kiếm"
                placeholder="Tên môn học, mã môn, mô tả..."
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
                  onClick={fetchCourses}
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

      {/* Courses List */}
      <Card>
        <CardContent>
          <Box display="flex" justifyContent="space-between" alignItems="center" mb={2}>
            <Typography variant="h6">
              Danh sách môn học ({filteredCourses.length} môn học)
            </Typography>
          </Box>
          
          <TableContainer component={Paper} variant="outlined">
            <Table>
              <TableHead>
                <TableRow>
                  <TableCell>#</TableCell>
                  <TableCell>Thông tin môn học</TableCell>
                  <TableCell>Mã môn</TableCell>
                  <TableCell>Tín chỉ</TableCell>
                  <TableCell>Giảng viên</TableCell>
                  <TableCell>Trạng thái</TableCell>
                  <TableCell>Ngày tạo</TableCell>
                  <TableCell align="center">Thao tác</TableCell>
                </TableRow>
              </TableHead>
              <TableBody>
                {filteredCourses.length === 0 ? (
                  <TableRow>
                    <TableCell colSpan={8} align="center" sx={{ py: 4 }}>
                      <Box textAlign="center">
                        <BookIcon sx={{ fontSize: 48, color: 'text.secondary', mb: 2 }} />
                        <Typography variant="h6" color="text.secondary">
                          Không tìm thấy môn học nào
                        </Typography>
                        <Typography variant="body2" color="text.secondary">
                          Hãy thử thay đổi bộ lọc hoặc thêm môn học mới
                        </Typography>
                      </Box>
                    </TableCell>
                  </TableRow>
                ) : (
                  filteredCourses.map((course, index) => (
                    <TableRow key={course.id} hover>
                      <TableCell>{index + 1}</TableCell>
                      <TableCell>
                        <Box>
                          <Typography variant="subtitle2" fontWeight="bold">
                            {course.name}
                          </Typography>
                          {course.description && (
                            <Typography variant="caption" color="text.secondary">
                              {course.description.length > 50 
                                ? course.description.substring(0, 50) + '...'
                                : course.description
                              }
                            </Typography>
                          )}
                        </Box>
                      </TableCell>
                      <TableCell>
                        <Typography variant="body2" fontWeight="bold">
                          {course.code}
                        </Typography>
                      </TableCell>
                      <TableCell>
                        <Box display="flex" alignItems="center">
                          <CreditCardIcon sx={{ mr: 1, fontSize: 16, color: 'text.secondary' }} />
                          <Typography variant="body2">
                            {course.credits} tín chỉ
                          </Typography>
                        </Box>
                      </TableCell>
                      <TableCell>
                        <Box display="flex" alignItems="center">
                          <PersonIcon sx={{ mr: 1, fontSize: 16, color: 'text.secondary' }} />
                          <Typography variant="body2">
                            {getTeacherName(course.teacher_id)}
                          </Typography>
                        </Box>
                      </TableCell>
                      <TableCell>
                        <Chip 
                          label={getStatusText(course.status)}
                          color={getStatusColor(course.status)}
                          size="small"
                        />
                      </TableCell>
                      <TableCell>
                        {new Date(course.created_at).toLocaleDateString('vi-VN')}
                      </TableCell>
                      <TableCell align="center">
                        <Box display="flex" gap={1} justifyContent="center">
                          <IconButton
                            size="small"
                            color="primary"
                            onClick={() => handleOpenDialog(course)}
                            title="Chỉnh sửa"
                          >
                            <EditIcon />
                          </IconButton>
                          <IconButton
                            size="small"
                            color="error"
                            onClick={() => handleDelete(course.id, course.name)}
                            title="Xóa môn học"
                          >
                            <DeleteIcon />
                          </IconButton>
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
          {editingCourse ? 'Chỉnh sửa môn học' : 'Thêm môn học mới'}
        </DialogTitle>
        <form onSubmit={handleSubmit}>
          <DialogContent>
            <Grid container spacing={2} sx={{ mt: 1 }}>
              <Grid item xs={12} sm={6}>
                <TextField
                  fullWidth
                  label="Tên môn học"
                  value={formData.name}
                  onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                  required
                />
              </Grid>
              <Grid item xs={12} sm={6}>
                <TextField
                  fullWidth
                  label="Mã môn học"
                  value={formData.code}
                  onChange={(e) => setFormData({ ...formData, code: e.target.value })}
                  required
                />
              </Grid>
              <Grid item xs={12} sm={6}>
                <TextField
                  fullWidth
                  label="Số tín chỉ"
                  type="number"
                  value={formData.credits}
                  onChange={(e) => setFormData({ ...formData, credits: e.target.value })}
                  required
                />
              </Grid>
              <Grid item xs={12} sm={6}>
                <FormControl fullWidth>
                  <InputLabel>Giảng viên</InputLabel>
                  <Select
                    value={formData.teacher_id}
                    label="Giảng viên"
                    onChange={(e) => setFormData({ ...formData, teacher_id: e.target.value })}
                  >
                    <MenuItem value="">Chọn giảng viên</MenuItem>
                    {teachers.map((teacher) => (
                      <MenuItem key={teacher.id} value={teacher.id}>
                        {teacher.name}
                      </MenuItem>
                    ))}
                  </Select>
                </FormControl>
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
                    <MenuItem value="active">Đang mở</MenuItem>
                    <MenuItem value="inactive">Tạm đóng</MenuItem>
                    <MenuItem value="archived">Đã lưu trữ</MenuItem>
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
              {editingCourse ? 'Cập nhật' : 'Tạo môn học'}
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

export default Courses;
